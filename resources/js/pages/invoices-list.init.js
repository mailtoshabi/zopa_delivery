/*
Template Name: ZOPA - Food Drop
Author: Web Mahal Web Service
Website: https://webmahal.com/
Contact: webmahal@gmail.com
File: invoice list Js File
*/

// datatable
$(document).ready(function() {
    $('.datatable').DataTable({
        responsive: false
    });
    $(".dataTables_length select").addClass('form-select form-select-sm');
});


// flatpicker

flatpickr('.datepicker-range', {
    mode: "range",
    altInput: true,
    wrap: true
});
