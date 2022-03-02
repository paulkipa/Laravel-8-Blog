@extends('layout')
@section('head')
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
@endsection
<!-- main -->
@section('main')
    <main class="container" style="background-color: white">
        <section id="contact-us">
            <h1 style="padding-top: 50px;">Edit Category</h1>
            @include('includes.flash-message')
            <!-- contact info -->
            <div class="container">
                <!-- Contact Form -->
                <div class="contact-form">

                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @method('put')
                        @csrf

                        <!-- Ttile -->
                        <label for="name"><span>Name</span></label>
                        <input type="text" id="name" name="name" value="{{ $category->name }}" />
                        @error('name')
                            <p style="color: red; margin-top: 25px;">{{ $message }}</p>
                        @enderror


                        <!-- Button -->
                        <input type="submit" value="Submit" />
                    </form>
                </div>
            </div>
            <div class="create-categories">
                <a href="{{ route('categories.index') }}">Categories List <span>&#8594</span></a>
            </div>
        </section>
    </main>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('body');
    </script>
@endsection
