<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;    //Auth käyttöön, näytetään käyttäjän id:en mukaan olevat tehtävät
use App\Models\post;                    //Modelli käyttöön. (Modellin säännöt)
use Illuminate\Http\Request;            //Voidaan ottaa lomakkeesta oleva tieto ja tallentaa tietokantaan
use Illuminate\Support\Facades\DB;      //Query builder





class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');  #Kirjautunut käyttäjä pystyy käyttämään tätä controlleria.
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()                                 #Function joka näyttää käyttäjän id:en mukaan hänen tehtävänsä.
    {

        $id = Auth::id();                                   //kirjautuneen käyttäjän id muuttujaan

        #Valitaan posts taulusta user_id:en mukaan olevat tehtävät.
        $results = DB::table('users')                       //Innerjoin, käytetään taulu users
        ->join('posts', 'users.id', "=", 'posts.user_id')   //Linkitetään users->posts, users id = posts taulussa olevaan user_id
        ->where('posts.user_id', $id)                       //Valitaan posts taulusta user_id = käyttäjän id
        ->orderBy('posts.id', 'DESC')                       //Järjestyksessä posts id, uusin ensin. 
        ->get();                                            //Viimeistely toiminta

        return view('todo.index',['results' => $results]);  //viedään käyttäjä index sivulle ja näytetään index sivulla tiedot
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Ei käytössä, formi on bootrap modalissa. Jos formi olisi eri sivulla niin sitten tämä käyttöön.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) #function jolla otetaan tarvittavat tiedot lomakkeesta ja tallennetaan tietokantaan.
    {
        $request->validate([                                        //Määritetään mitä 'sallitaan' tietokantaan. 
            'tehtava' => 'required|unique:posts,tehtava'            //required eli jotain pitää olla ja jos on samalla tekstillä oleva tehtävä
        ]);                         


        $aktiivinen = "Aktiivinen";         //muttujassa tekstiä, tämä tallenetaan defaulttina uuteen tehtävään.
        $post =  new post();                //uusi talletus, käyttäen post model
        $post->tehtava = $request->tehtava; //tallennetaan rowiin nimeltä tehtava, vaaditaan tiedot lomakkeelta inputin name="tehtava"
        $post->status =  $aktiivinen;       //ensimmäinen tehtävän lisäyksessä status on aktiivinen
        $post->user_id = Auth::id();        //tallennetaan käyttäjän id, posts tauluun user_id
        $post->save();                      //tallennetaan koko paketti

        return redirect()->route("todo.index")->with('message','Uusi Tehtävä lisätty!');    #Ohjataan käyttäjä todo.index sivulle, viestin kanssa
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Ei käytössä, jos olisi enemmän tietoa(dataa) niin sitten käyttöön.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)           #Function joka muuttaa halutun id:en Statusta
    {

        $valmis = "Valmis";             //Muuttujassa tekstiä
        $post = post::find($id);        //Etsi taulusta tämä id
        $post->status =  $valmis;       //Muuta status = muuttuja
        $post->save();                  //Tallennetaan koko paketti

        return redirect()->back();      #Ohjataan käyttäjä takaisin index sivulle
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)   #Function jolla päivitetään tietyn id:en tehtävää
    {
        $request->validate([                                    //Määritetään mitä 'sallitaan' tietokantaan. 
            'paivitetty' => 'required|unique:posts,tehtava'     //required eli jotain pitää olla ja jos on samalla tekstillä oleva tehtävä
        ]);

        $post = post::find($id);                 //Etsi taulusta tämä id  
        $post->tehtava = $request->paivitetty;   //Tallennetaan rowiin nimeltä tehtava, vaaditaan tiedot lomakkeelta inputin name="paivitetty"
        $post->save();                           //Tallennetaan koko paketti

        return redirect()->route("todo.index")->with('paivitetty','Tehtävä Päivitetty!');   #Ohjataan käyttäjä index sivulle, viestin kanssa.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)                    #Poistetaan haluttu id tietokannasta
    {
        post::where('id',$id)->delete();            //Poista taulusta tämä id 

        return redirect()->route("todo.index")->with('poisto','Tehtävä Poistettu!');    #Ohjataan käyttäjä takaisin index sivulle, viestin kanssa.
    }
}
