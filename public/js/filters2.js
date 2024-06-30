window.onload = () => {
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
