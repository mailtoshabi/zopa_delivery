<?php

namespace App\Exports;

use App\Models\DailyMeal;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DailyMealsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $date;
    protected $kitchenId;
    protected $isAdmin;
    protected $links = [];

    public function __construct($date = null, $kitchenId = null, $isAdmin = false)
    {
        $this->date = $date ?? Carbon::today()->toDateString();
        $this->kitchenId = $kitchenId;
        $this->isAdmin = $isAdmin;
    }

    public function collection()
    {
        $query = DailyMeal::with([
            'customer.kitchen',
            'dailyAddons.addon'
        ])
        ->whereDate('date', $this->date)
        ->whereHas('customer', function ($q) {
            $q->where('customer_type', 'individual');

            // Apply kitchen filter if selected
            if ($this->kitchenId) {
                $q->where('kitchen_id', $this->kitchenId);
            }
        });

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Customer Name',
            'Phone',
            'Office Name',
            'Place',
            'Land Mark',
            'Location Name',
            'Kitchen',
            'Item',
            'Quantity',
            'Addons',
            'Status'
        ];
    }

    public function map($meal): array
    {
        $locationName = $meal->customer->location_name;

        if ($meal->customer->latitude && $meal->customer->longitude) {
            $googleMapsUrl = "https://maps.google.com/?q={$meal->customer->latitude},{$meal->customer->longitude}";
            $this->links[] = $googleMapsUrl; // store link for later
        } else {
            $this->links[] = null;
        }

        return [
            $meal->date->format('Y-m-d'),
            $meal->customer->name,
            $meal->customer->phone,
            $meal->customer->office_name,
            $meal->customer->city,
            $meal->customer->landmark,
            $locationName, // just plain text for now
            optional($meal->customer->kitchen)->display_name,
            $meal->walletGroup->name,
            $meal->quantity,
            $addonsList ?? 'None',
            $meal->is_delivered ? 'Delivered' : 'Pending',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($this->links as $index => $url) {
                    if ($url) {
                        // Row index +2 because headings take the first row, and array index starts at 0
                        $cell = 'G' . ($index + 2); // G = Location column
                        $event->sheet->getDelegate()->getCell($cell)->getHyperlink()->setUrl($url);
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->getColor()->setARGB('0000FF');
                        $event->sheet->getDelegate()->getStyle($cell)->getFont()->setUnderline(true);
                    }
                }
            }
        ];
    }
}
