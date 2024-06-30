window.onload = () => {

const music = new Audio();
    
const auctionsContentlist = document.querySelector("#auction_content");
const imgList = auctionsContentlist.querySelectorAll("img");

console.log('testPlayer');


const songs = [
    {
        
        id:'1',
        songName:` On My Way <br> 
        <div class="subtitle">Alan Walker</div>`,
        poster: "img/1.jpg"
    }
]



Array.from(document.getElementsByClassName('songItem')).forEach((element, i)=>{
    element.getElementsByTagName('img')[0].src = songs[i].poster;
    element.getElementsByTagName('h5')[0].innerHTML = songs[i].songName;
})


let masterPlay = document.getElementById('masterPlay');
let wave = document.getElementsByClassName('wave')[0];

masterPlay.addEventListener('click',()=>{
    if (music.paused || music.currentTime <=0) {
        music.play();
        masterPlay.classList.remove('bi-play-fill');
        masterPlay.classList.add('bi-pause-fill');
        wave.classList.add('active2');
    } else {
        music.pause();
        masterPlay.classList.add('bi-play-fill');
        masterPlay.classList.remove('bi-pause-fill');
        wave.classList.remove('active2');
    }
} )


const makeAllPlays = () =>{
    Array.from(document.getElementsByClassName('playListPlay')).forEach((element)=>{
            element.classList.add('bi-play-circle-fill');
            element.classList.remove('bi-pause-circle-fill');
    })
}
const makeAllBackgrounds = () =>{
    Array.from(document.getElementsByClassName('songItem')).forEach((element)=>{
            element.style.background = "rgb(105, 105, 170, 0)";
    })
}

//let index = 0;
let poster_master_play = document.getElementById('poster_master_play');
let title = document.getElementById('title');
//let subtitle = document.getElementById('subtitle');
Array.from(document.getElementsByClassName('playListPlay')).forEach((element)=>{
    element.addEventListener('click', (e)=>{
        document.querySelector('.master_play').style.visibility = 'visible';
        //console.log(e.target);
        if (e.target instanceof HTMLImageElement) {
            image = e.target.src;
            music_url = e.target.parentNode.parentNode.querySelector('i').id;
            url=e.target.parentNode.parentNode.parentNode.querySelector('a').querySelector('article');
            
        }
        else{
            music_url= e.target.id;
            image = e.target.parentNode.querySelector('img').src;
            url=e.target.parentNode.parentNode.querySelector('a').querySelector('article');
        }
        console.log(image);
        console.log(music_url);
        //makeAllPlays();
        /*Sert à modif l'icone sur la div mais on en a pas besoin
        e.target.classList.remove('bi-play-circle-fill');
        e.target.classList.add('bi-pause-circle-fill');
        */
        music.src = music_url;
        poster_master_play.src =image;
        music.play();
        title.innerHTML =url.querySelector('h4').textContent+"<br><div class=\"subtitle\">"+url.querySelector('section').querySelector('li').textContent+"</div>";
        console.log(url.querySelector('section').querySelector('li').textContent);
        masterPlay.classList.remove('bi-play-fill'); //Bouton play pause dans la bar music
        masterPlay.classList.add('bi-pause-fill');
        wave.classList.add('active2');
        music.addEventListener('ended',()=>{
            masterPlay.classList.add('bi-play-fill');
            masterPlay.classList.remove('bi-pause-fill');
            wave.classList.remove('active2');
        })
        makeAllBackgrounds();
        //Array.from(document.getElementsByClassName('songItem'))[`${index-1}`].style.background = "rgb(105, 105, 170, .1)";   Jsp à quoi elle sert
    })
})


let currentStart = document.getElementById('currentStart');
let currentEnd = document.getElementById('currentEnd');
let seek = document.getElementById('seek');
let bar2 = document.getElementById('bar2');
let dot = document.getElementsByClassName('dot')[0];

music.addEventListener('timeupdate',()=>{
    let music_curr = music.currentTime;
    let music_dur = music.duration;

    let min = Math.floor(music_dur/60);
    let sec = Math.floor(music_dur%60);
    if (sec<10) {
        sec = `0${sec}`
    }
    currentEnd.innerText = `${min}:${sec}`;

    let min1 = Math.floor(music_curr/60);
    let sec1 = Math.floor(music_curr%60);
    if (sec1<10) {
        sec1 = `0${sec1}`
    }
    currentStart.innerText = `${min1}:${sec1}`;

    let progressbar = parseInt((music.currentTime/music.duration)*100);
    seek.value = progressbar;
    let seekbar = seek.value;
    bar2.style.width = `${seekbar}%`;
    dot.style.left = `${seekbar}%`;
})

seek.addEventListener('change', ()=>{
    music.currentTime = seek.value * music.duration/100;
})

music.addEventListener('ended', ()=>{
    masterPlay.classList.add('bi-play-fill');
    masterPlay.classList.remove('bi-pause-fill');
    wave.classList.remove('active2');
})


let vol_icon = document.getElementById('vol_icon');
let vol = document.getElementById('vol');
let vol_dot = document.getElementById('vol_dot');
let vol_bar = document.getElementsByClassName('vol_bar')[0];

vol.addEventListener('change', ()=>{
    if (vol.value == 0) {
        vol_icon.classList.remove('bi-volume-down-fill');
        vol_icon.classList.add('bi-volume-mute-fill');
        vol_icon.classList.remove('bi-volume-up-fill');
    }
    if (vol.value > 0) {
        vol_icon.classList.add('bi-volume-down-fill');
        vol_icon.classList.remove('bi-volume-mute-fill');
        vol_icon.classList.remove('bi-volume-up-fill');
    }
    if (vol.value > 50) {
        vol_icon.classList.remove('bi-volume-down-fill');
        vol_icon.classList.remove('bi-volume-mute-fill');
        vol_icon.classList.add('bi-volume-up-fill');
    }

    let vol_a = vol.value;
    vol_bar.style.width = `${vol_a}%`;
    vol_dot.style.left = `${vol_a}%`;
    music.volume = vol_a/100;
})



let back = document.getElementById('back');
let next = document.getElementById('next');

back.addEventListener('click', ()=>{
    index -= 1;
    if (index < 1) {
        index = Array.from(document.getElementsByClassName('songItem')).length;
    }
    music.src = `audio/${index}.mp3`;
    poster_master_play.src =`img/${index}.jpg`;
    music.play();
    let song_title = songs.filter((ele)=>{
        return ele.id == index;
    })

    song_title.forEach(ele =>{
        let {songName} = ele;
        title.innerHTML = songName;
    })
    makeAllPlays()

    document.getElementById(`${index}`).classList.remove('bi-play-fill');
    document.getElementById(`${index}`).classList.add('bi-pause-fill');
    makeAllBackgrounds();
    Array.from(document.getElementsByClassName('songItem'))[`${index-1}`].style.background = "rgb(105, 105, 170, .1)";
    
})
next.addEventListener('click', ()=>{
    index -= 0;
    index += 1;
    if (index > Array.from(document.getElementsByClassName('songItem')).length) {
        index = 1;
        }
    music.src = `audio/${index}.mp3`;
    poster_master_play.src =`img/${index}.jpg`;
    music.play();
    let song_title = songs.filter((ele)=>{
        return ele.id == index;
    })

    song_title.forEach(ele =>{
        let {songName} = ele;
        title.innerHTML = songName;
    })
    makeAllPlays()

    document.getElementById(`${index}`).classList.remove('bi-play-fill');
    document.getElementById(`${index}`).classList.add('bi-pause-fill');
    makeAllBackgrounds();
    Array.from(document.getElementsByClassName('songItem'))[`${index-1}`].style.background = "rgb(105, 105, 170, .1)";
    
})

/*
let left_scroll = document.getElementById('left_scroll');
let right_scroll = document.getElementById('right_scroll');
let pop_song = document.getElementsByClassName('pop_song')[0];

left_scroll.addEventListener('click', ()=>{
    pop_song.scrollLeft -= 330;
})
right_scroll.addEventListener('click', ()=>{
    pop_song.scrollLeft += 330;
})


let left_scrolls = document.getElementById('left_scrolls');
let right_scrolls = document.getElementById('right_scrolls');
let item = document.getElementsByClassName('item')[0];

left_scrolls.addEventListener('click', ()=>{
    item.scrollLeft -= 330;
})
right_scrolls.addEventListener('click', ()=>{
    item.scrollLeft += 330;
})
*/


console.log('testfilter');
    const FiltersCatForm = document.querySelector("#filters_categories");
    const FiltersInstrumentForm = document.querySelector("#filters_instrument");
    const FiltersMoodsForm = document.querySelector("#filters_moods");
    const SearchForm = document.querySelector("#search_form");

        /*  filters-mobile  */
    
        const filtersmobile = document.querySelector(".filters-mobile")
        const accordion = document.querySelector(".accordion-mobile")
        filtersmobile.addEventListener('click',()=>{
            accordion.classList.toggle('mobile-menu')
            filtersmobile.classList.toggle('fermer')
            
        })

    
    console.log(FiltersCatForm);
    console.log(FiltersInstrumentForm);
    console.log(FiltersMoodsForm);
    
    /*
    // On récupère les données du formulaire
    const Form = new FormData(FiltersCatForm);

    keys=[];
    Form.forEach((value, key) => { 
        keys.push(key);
    });
    console.log(keys);
    const Checkbox_categories = document.querySelectorAll("#filters_categories input");
    console.log(Checkbox_categories);
    Checkbox_categories.forEach((value,key)=>{i=0;
        //while(keys.get(0).g!=)
    });
*/
    // On boucle sur les input
    Array.from(document.querySelectorAll("#filters_categories input,#filters_instrument input,#filters_moods input,#search_form input").forEach(input => {
        input.addEventListener("change", () => {
            // Ici on intercepte les clics
            // On récupère les données du formulaire
            const FormCat = new FormData(FiltersCatForm);
            const FormInstru = new FormData(FiltersInstrumentForm);
            const FormMood = new FormData(FiltersMoodsForm);
            const FormSearch = new FormData(SearchForm);

            // On récupère l'url active
            const Url = new URL(window.location.href);

            // On fabrique la "queryString"
            const Params = new URLSearchParams();

            /*
            Form.forEach((value, key) => {
                console.log(key,value);
                Params.append(key, value);
            });*/
            
            Parametre = '';
            Premier_param = true;
            
            FormCat.forEach((value, key) => { 
                //console.log(key,value);
                if(Premier_param){
                    Parametre=Parametre+key+"="+value;
                    Premier_param = false;
                }else{
                    Parametre=Parametre+"&"+key+"="+value;
                }
               
            });

            FormInstru.forEach((value, key) => { 
                //console.log(key,value);
                if(Premier_param){
                    Parametre=Parametre+key+"="+value;
                    Premier_param = false;
                }else{
                    Parametre=Parametre+"&"+key+"="+value;
                }
               
            });

            FormMood.forEach((value, key) => { 
                console.log(key,value);
                if(Premier_param){
                    Parametre=Parametre+key+"="+value;
                    Premier_param = false;
                }else{
                    Parametre=Parametre+"&"+key+"="+value;
                }
               
            });

            FormSearch.forEach((value, key) => {
                if(value!=''){
                    console.log(key,value);
                if(Premier_param){
                    Parametre=Parametre+key+"="+value;
                    Premier_param = false;
                }else{
                    Parametre=Parametre+"&"+key+"="+value;
                }
                }
            });

            Parametre=Parametre+"&page=1";
            
            console.log(Parametre);
            console.log(Url);
            
            // On lance la requête ajax
            fetch(Url.pathname + "?" + Parametre + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response=> //{console.log(response)}
                response.json()
            ).then(data => {//console.log(data);
                
                // On va chercher la zone de contenu
                const content = document.querySelector("#auction_content");

                // On remplace le contenu
                content.innerHTML = data.content;
                
                // On met à jour l'url
                history.pushState({}, null, Url.pathname + "?" + Parametre);
                
            }).catch(e => alert(e));
            
        });
    }));

    
}
