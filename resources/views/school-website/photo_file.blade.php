@extends('layouts.school.master')
@section('title')
    Photos
@endsection
@section('content')
    <style>
        ul {
            padding-left: unset !important;
        }

        
        
    </style>

    <div class="breadcrumb">
        <div class="container">
            <div class="contentWrapper">
                <span class="title">
                    Gallery
                </span>
                <span>
                    <a href="{{ url('/') }}" class="home">Home</a>
                    <span><i class="fa-solid fa-caret-right"></i></span>
                    <span class="home">Gallery</span>
                    <span><i class="fa-solid fa-caret-right"></i></span>
                    <span class="page">Photos</span>
                </span>
            </div>
        </div>
    </div>


    <section class="photosGallery commonMT commonWaveSect">
        <div class="container">
            <div id="Center">
                <ul id="waterfall"></ul>
            </div>
        </div>
    </section>

    <div id="lightbox" class="lightbox">
        <div class="lightbox-size">
            <span class="close"><i class="fa fa-close"></i></span>
            <img class="lightbox-content" id="lightbox-img">
            <iframe class="lightbox-content responsive-iframe" id="lightbox-video" allowfullscreen></iframe>
            <div class="caption" id="caption"></div>
        </div>
        
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#waterfall').NewWaterfall({
                width: 360,
                delay: 100,
            });
        });

        function random(min, max) {
            return min + Math.floor(Math.random() * (max - min + 1))
        }
        var loading = false;
        var dist = 500;
        var num = 1;
        var count = 0; // Current count of loaded items
        var maxCount = {{ count($photos->file) }};
        setInterval(function() {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() - dist && !loading && count <
                maxCount) {
                loading = true;
                @foreach ($photos->file as $row)
                    // var height = random(200, 400);
                    var height = 300;
                    // Change detailArr1 to detailArr if added lightbox
                    // And add "{{ asset('assets/school/images/photosArrIcon.png') }}" image src
                    $("#waterfall").append("<li><div class='m-2 upperBigImg1' style='height:" + height +
                        "px'><img class='thumbnail' style='height:" + height +
                        "px;width: 100%; aspect-ratio: 1 / 1; object-fit: contain;' src='{{ $row->file_url }}' alt=''><div class='detailArr'> <img src='{{ asset('assets/school/images/photosArrIcon.png') }}' alt=''></div></div></li>"
                    );

                    count++;
                @endforeach
                loading = false;
            }
        }, 60);

    </script>
@endsection
