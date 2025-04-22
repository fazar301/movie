<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MovieService
{
    public function getAllMovies($search = null)
    {
        $query = Movie::latest();
        
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        
        return $query->paginate(6)->withQueryString();
    }

    public function getMovieById($id)
    {
        return Movie::findOrFail($id);
    }

    public function createMovie($validatedData)
    {
        if (isset($validatedData['foto_sampul'])) {
            $validatedData['foto_sampul'] = $this->storeImage($validatedData['foto_sampul']);
        }

        return Movie::create($validatedData);
    }

    public function updateMovie($id, $validatedData)
    {
        $movie = Movie::findOrFail($id);

        if (isset($validatedData['foto_sampul'])) {
            $this->deleteImage($movie->foto_sampul);
            $validatedData['foto_sampul'] = $this->storeImage($validatedData['foto_sampul']);
        }

        $movie->update($validatedData);
        return $movie;
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);
        $this->deleteImage($movie->foto_sampul);
        $movie->delete();
    }

    private function storeImage($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('images/movie_covers', $filename, 'public');
        return $path;
    }

    private function deleteImage($filename)
    {
        if (Storage::disk('public')->exists($filename)) {
            Storage::disk('public')->delete($filename);
        }
    }
} 