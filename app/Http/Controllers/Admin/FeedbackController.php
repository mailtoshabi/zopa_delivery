<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('customer')->latest()->paginate(20);
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function togglePublic($id)
    {
        $feedback = Feedback::findOrFail(decrypt($id));
        $feedback->is_public = !$feedback->is_public;
        $feedback->save();

        return redirect()->back()->with('success', 'Feedback visibility updated successfully.');
    }

    public function replyAjax(Request $request, Feedback $feedback)
    {
        $request->validate([
            'reply' => 'nullable|string|max:1000',
            'is_public' => 'nullable|boolean',
        ]);

        $feedback->reply = $request->reply;
        $feedback->is_public = $request->has('is_public');
        $feedback->replied_at = now();
        $feedback->save();

        return response()->json([
            'success' => true,
            'message' => 'Reply saved successfully.',
            'reply' => $feedback->reply,
            'replied_at' => $feedback->replied_at->format('d M Y h:i A'),
            'is_public' => $feedback->is_public,
        ]);
    }

}

