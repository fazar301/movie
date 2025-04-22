<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\MovieService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index()
    {
        $movies = $this->movieService->getAllMovies(request('search'));
        return view('homepage', compact('movies'));
    }

    public function detail($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return view('detail', compact('movie'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('input', compact('categories'));
    }

    public function store(StoreMovieRequest $request)
    {
        $this->movieService->createMovie($request->validated());
        return redirect('/')->with('success', 'Film berhasil ditambahkan.');
    }

    public function data()
    {
        $movies = $this->movieService->getAllMovies();
        return view('data-movies', compact('movies'));
    }

    public function form_edit($id)
    {
        $movie = $this->movieService->getMovieById($id);
        $categories = Category::all();
        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(UpdateMovieRequest $request, $id)
    {
        $this->movieService->updateMovie($id, $request->validated());
        return redirect('/movies/data')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->movieService->deleteMovie($id);
        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }
}
