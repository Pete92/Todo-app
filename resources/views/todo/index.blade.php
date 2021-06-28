@extends('layouts.app') <!--Käytetään app.blade.php:en pohjaa jossa on yield('alueita')-->

<!--Lomake sectionin aloitus, Tällä tehdään Uusi tehtävä-->
@section('lomake')
<div class="container">
  <h2 class="text-center my-5">ToDo Lista</h2>
  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary float-right mb-4" data-toggle="modal" data-target="#uusitehtava">
    Lisää Uusi
  </button>
</div>

<!-- Uuden tehtävän Modal -->
<div class="modal fade" id="uusitehtava" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Lisää uusi tehtävä</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('todo.store')}}"> <!--Resrouce controllerissa on reitti, todo.store joka kutsuuu store functionita-->
          @csrf
            <div class="mb-3">
                <input type="text" name="tehtava" class="form-control" placeholder="Aja nurmikko...">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Sulje</button>
        <button type="submit" class="btn btn-success">Lisää</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal lopetus -->
@endsection


<!-- content section aloitus, johon tulostetaan tietokannasta oleva data-->
@section('content')
  <div class="container mt-5">
    <!-- Jos formeissa ilmenee onglemia niin tulostetaa errorit-->
    @if($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show w-25 p-3 mb-4" role="alert">
      <strong>{{$error}}</strong>
    </div>
    @endforeach
  @endif
     <!--Jos message sessionissa jotain--->
     @if(session('message'))
     <div class="alert alert-success alert-dismissible fade show w-25 p-3" role="alert">
       <strong>{{session('message')}}</strong>
     </div>
     @endif
     <!--Jos poisto sessionissa jotain--->
     @if(session('poisto'))
     <div class="alert alert-danger alert-dismissible fade show w-25 p-3" role="alert">
       <strong>{{session('poisto')}}</strong>
     </div>
     @endif
     <!--Jos paivitetty sessionissa jotain--->
     @if(session('paivitetty'))
     <div class="alert alert-primary alert-dismissible fade show w-25 p-3" role="alert">
       <strong>{{session('paivitetty')}}</strong>
     </div>
     @endif
    <table class="table table-hover text-center">
      <thead>
          <tr>
            <th scope="col">Aika</th>
            <th scope="col">Tehtävä</th>
            <th scope="col">Status</th>
            <th scope="col">Toiminnot</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($results as $result)  <!--Foreach looppi jolla saadaan tietokannasta data-->
            <tr>
              <!--Muutetaan tietokannasta oleva aika, poistetaan kellon aika. Päivämäärä näkyy.-->
              <th scope="row">{{ date('d-m-Y', strtotime($result->created_at))}}</th>
              <td>{{$result->tehtava}}</td> <!--Tulostetaan tehtavässä oleva teksti-->
              <td>{{$result->status}}</td>  <!--Tulostetaan statuksessa oleva teksti-->
              <td>
                <div class="btn-group">
                <!--Nappi joka avaa modalin kun halutaan muokata/päivittää-->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#muokkaus{{$result->id}}"><i class="fas fa-pen-alt"></i></button> 

                <form action="{{route('todo.destroy',$result->id)}}" method="POST">  <!--resource controllerin reitti. todo.destroy.{id} joka kutsuu destroy functionita-->
                  @csrf <!--Suojaa formin joka estää luvattomien komentojen lähettämisen-->
                  @method('DELETE') <!--Tarvitaan jotta poisto onnistuu -->
                <button type="submit" class="btn btn-danger mx-2"><i class="fas fa-trash-alt"></i></button>
                </form>

                <form action="/todo/{{$result->id}}/edit"> <!--resrource controllerin reitti. todo.{id}.edit joka kutsuu edit functionita-->
                  @csrf         <!--Suojaa formin joka estää luvattomien komentojen lähettämisen-->
                  @method('PUT')<!--Edit funcition tarvitseet tämän-->  
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i></button></div>
                </form>
              </td>
              <!-- muokkaus/päivitettävän Modal aloitus-->
              <div class="modal fade" id="muokkaus{{$result->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('todo.update',$result->id)}}" method="POST"> <!--Resource controllin reitti. todo.update.{id} joka kutsuu update functionita-->
                        @method('PUT') <!--Update function tarvitsee-->
                        @csrf          <!--Suojaa formin joka estää luvattomien komentojen lähettämisen--> 
                          <div class="mb-3">
                              <input type="text" name="paivitetty" class="form-control" placeholder="Päivitä tehtävää" value="{{$result->tehtava}}">
                          </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Sulje</button>
                      <button type="submit" class="btn btn-primary">Muokkaa</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>
              <!-- Modal lopetus-->
            </tr>
        @endforeach <!--Foreach lopetus-->
        </tbody>
    </table>
  </div>
@endsection <!--conten sectionini lopetus-->