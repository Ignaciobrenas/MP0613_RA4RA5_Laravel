<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{

    /**
     * Read films from storage
     */
    public static function readFilms(): array {
        $films = Storage::json('/public/films.json');
        return $films;
    }
    /**
     * List films older than input year 
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listOldFilms($year = null)
    {        
        $old_films = [];
        if (is_null($year))
        $year = 2000;
    
        $title = "Listado de Pelis Antiguas (Antes de $year)";    
        $films = FilmController::readFilms();

        foreach ($films as $film) {
        //foreach ($this->datasource as $film) {
            if ($film['year'] < $year)
                $old_films[] = $film;
        }
        return view('films.list', ["films" => $old_films, "title" => $title]);
    }
    /**
     * List films younger than input year
     * if year is not infomed 2000 year will be used as criteria
     */
    public function listNewFilms($year = null)
    {
        $new_films = [];
        if (is_null($year))
            $year = 2000;

        $title = "Listado de Pelis Nuevas (Después de $year)";
        $films = FilmController::readFilms();

        foreach ($films as $film) {
            if ($film['year'] >= $year)
                $new_films[] = $film;
        }
        return view('films.list', ["films" => $new_films, "title" => $title]);
    }
    /**
     * List all films or filter them by year.
     */
    public function listFilmsByYear($year = null)
    {
        return $this->filterFilms(
            $year,
            "Listado de todas las pelis filtrado x año",
            fn (array $film): bool => $film['year'] == $year
        );
    }

    /**
     * List all films or filter them by genre.
     */
    public function listFilmsByGenre($genre = null)
    {
        $targetGenre = is_null($genre) ? '' : mb_strtolower($genre, 'UTF-8');

        return $this->filterFilms(
            $genre,
            "Listado de todas las pelis filtrado x categoria",
            fn (array $film): bool => mb_strtolower($film['genre'], 'UTF-8') === $targetGenre
        );
    }

    /**
     * Return all films when no filter is given, or filter them with the predicate.
     */
    private function filterFilms($filterValue, string $filteredTitle, callable $predicate)
    {
        $films = FilmController::readFilms();

        if (is_null($filterValue)) {
            return view('films.list', ["films" => $films, "title" => "Listado de todas las pelis"]);
        }

        $films_filtered = array_values(array_filter($films, $predicate));

        return view("films.list", ["films" => $films_filtered, "title" => $filteredTitle]);
    }
}
