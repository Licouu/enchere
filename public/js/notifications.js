var box  = document.getElementById('box');
var down = false;
    

function toggleNotifi(){
    if (down) {
        box.style.opacity = 0;
        down = false;
    }else {
        box.style.opacity = 1;
        down = true;
    }
}

function removeElementsByClass(className){
    const elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}
function onClickBtnNotif(event){
    event.preventDefault();
    if(down){
        const notificationContainer = document.getElementById("box");
        axios.get('/notification').then(function (response){
            const array = response.data;
            array.sort((a, b) => new Date(b.date) - new Date(a.date));
            array.forEach(notification => {
                const date = new Date(notification.date);
                const notificationDiv = document.createElement('div');
                notificationDiv.classList.add("notifi-item");
                notificationDiv.innerHTML = `<div><img id="imag" src="../assets/images/${notification.forAuction.Beat.image}" alt="beatImage"></div><div class="text">
                <h4>${notification.message}<i class="bi bi-x" data-id="${notification.id}"></i></h4>    
                <p>@${notification.forAuction.beatmaker.username} - ${notification.forAuction.Beat.name} ${date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds()}</p>
                </div>`;
                notificationContainer.appendChild(notificationDiv);
                notificationContainer.addEventListener('click', function(){
                    fetch("/auction/"+notification.forAuction.id)
                        .then(response => {
                            if(!response.ok){
                                throw new Error(response.statusText);
                            }
                        })
                        .then(data => {
                            //si la requête a réussi, redirige l'utilisateur vers la page souhaitée
                            window.location.href = "/auction/"+notification.forAuction.id;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                });
            });
            const iconDelete = document.querySelectorAll('.bi-x');
            iconDelete.forEach(icon => {
                icon.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const notificationId = event.target.closest('.notifi-item').querySelector('.bi-x').dataset.id;
                    console.log(notificationId);
                    fetch(`/notification/${notificationId}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        event.target.closest('.notifi-item').remove();
                        console.log("test");
                        axios.get('/nbnotif')
                        .then(function (response) {
                            if (response.data.nbNotif <= 0) {
                                const redCircle = document.getElementById("redCircle");
                                redCircle.remove();
                                down = false;
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    })
                    .catch(error => console.log(error));
                });
            });  
        })
    } else {
        removeElementsByClass("notifi-item");
        axios.get('/nbnotif')
            .then(function (response) {
                if (response.data.nbNotif > 0) {
                    const redCircle = document.createElement("redCircle");
                    const containerRedCircle = document.getElementById("containRedCircle")
                    containerRedCircle.appendChild(redCircle);
                } else {
                    const redCircle = document.getElementById("redCircle");
                    redCircle.remove();
                    down = false;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}
document.getElementById("bell").addEventListener('click', onClickBtnNotif);


function checkNbNotif(){
    const verifExistRougePoint = document.getElementById("redCircle");
    axios.get('/nbnotif')
  .then(function (response) {
    if (response.data.nbNotif > 0 && verifExistRougePoint == null) {
        const redCircle = document.createElement("span");
        redCircle.id = "redCircle";
        const containerRedCircle = document.getElementById("containRedCircle")
        containerRedCircle.appendChild(redCircle);
    }
  })
  .catch(function (error) {
    console.log(error);
  });
}

checkNbNotif();

setInterval(function() {
// appelle la fonction de mise à jour de la page ici
    checkNbNotif();
}, 5000);
