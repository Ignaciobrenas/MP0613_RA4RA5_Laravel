<h1>{{$title}}</h1>

@if(empty($totalFilms))
    <FONT COLOR="red">No se ha encontrado ninguna película</FONT>
@else
    <p>Total de películas: {{$totalFilms}}</p>
@endif
