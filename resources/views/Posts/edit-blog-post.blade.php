@extends('layout')
@section('head')
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
@endsection
<!-- main -->
@section('main')
    <main class="container" style="background-color: white">
        <section id="contact-us">
            <h1 style="padding-top: 50px;">Edit Post</h1>
            @include('includes.flash-message')
            <!-- contact info -->
            <div class="container">
                <!-- Contact Form -->
                <div class="contact-form">

                    <form action="{{ route('blog.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @method('put')
                        @csrf

                        <!-- Ttile -->
                        <label for="title"><span>Title</span></label>
                        <input type="text" id="title" name="title" value="{{ $post->title }}" />
                        @error('title')
                            <p style="color: red; margin-top: 25px;">{{ $message }}</p>
                        @enderror
                        <!-- Image -->
                        <label for="image"><span>Image</span></label>
                        <input type="file" id="image" name="image" />
                        @error('image')
                            <p style="color: red; margin-top: 25px;">{{ $message }}</p>
                        @enderror
                        <!-- Body -->
                        <label for="body"><span>Body</span></label>
                        <textarea id="body" name="body" value="">{{ $post->body }} </textarea>
                        @error('body')
                            <p style="color: red; margin-top: 25px;">{{ $message }}</p>
                        @enderror
                        <!-- Button -->
                        <input type="submit" value="Submit" />
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('body');
    </script>
@endsection
