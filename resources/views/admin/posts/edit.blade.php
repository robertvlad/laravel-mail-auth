@extends('layouts.admin');

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="py-3">
                <h2>Modifica Post</h2>
            </div>
            <div class="d-flex gap-3 pb-3">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">Annulla</a>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>                        
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
                <form action="{{ route('admin.posts.update', ['post' => $post['slug']])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="control-label">TITOLO</label>
                        <input type="text" class="form-control" placeholder="Titolo" id="title" name="title" value="{{ old('title') ?? $post['title']}}">
                        @error('title')
                            <div class="text-danger">** {{ $message }}</div>                            
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label">CONTENUTO</label>
                        <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenuto" class="form-control">{{ old('content') ?? $post['content']}}</textarea>
                    </div>
                    <div class="form-group my-3">
                        <label class="control-label">Tipo </label>
                        <select class="form-comntrol" name="type_id" id="type_id">
                            <option value="">Seziona il tipo</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ $type->id == old('type_id', $post->type_id) ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group my-3">
                        <div class="control-label">Technologies</div>
                        @foreach ($technologies as $technology)
                        <div class="form-check @error('technologies') is-invalid @enderror">

                            @if ($errors->any())
                            <input type="checkbox" value="{{ $technology->id }}" class="form-check-input" name="technologies[]" {{ in_array($technology->id, old('technologies', [] )) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $technology->name }}</label>                                
                            @else
                            <input type="checkbox" value="{{ $technology->id }}" class="form-check-input" name='technologies[]' {{ $post->technoligies->contains($technology) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $technology->name }}</label>                                
                            @endif                            
                        </div>                            
                        @endforeach

                        @error('technologies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="from-group my-3">
                        <label class="control-label">Copertina precedente</label>
                        <img src="{{ asset('storage/' .$post->cover_image ) }}" class="w-25 my-3">
                        <input type="file" name="cover_image" id="cover-image" class="form-control @error('cover_image')is-invalid @enderror">
                        @error('cover_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group my-2">
                        <button type="submit" class="btn btn-success">Salva</button>                        
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>

@endsection