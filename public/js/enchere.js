console.log('enchère.js');

document.querySelector('#btnChangeBgg').addEventListener('click', () => {
    console.log("sure?");
    let input = document.querySelector('#offer_form_price');
    console.log(input);
    let inputValue = $("#offer_form_price").val();


    Confirm.open({
    title: 'Are you sure ?',
    message: 'Do you want to bid of '  +  inputValue  + ' € ?',/*formulaire.prix.value*/
    //si bouton = ok alors on envoie le formulaire
    onok: () => {
        /*document.formulaire.submit();*/
        document.querySelector('#btnChangeBg').click();
        console.log('ok');
    }
      })
    });





    const Confirm = {
        open (options) {
          options = Object.assign({}, {
              title: '',
              message: '',
              okText: 'Confirm',
              cancelText: 'Cancel',
              onok: function () {},
              oncancel: function () {}
          }, options);
          
          const html = `
              <div class="confirm">
                  <div class="confirm__window">
                      <div class="confirm__titlebar">
                          <span class="confirm__title">${options.title}</span>
                          <button class="confirm__close">&times;</button>
                      </div>
                      <div class="confirm__content">${options.message}</div>
                      <div class="confirm__buttons">
                          <button class="confirm__button confirm__button--ok confirm__button--fill">${options.okText}</button>
                          <button class="confirm__button confirm__button--cancel">${options.cancelText}</button>
                      </div>
                  </div>
              </div>
          `;
    
          const template = document.createElement('template');
          template.innerHTML = html;
              
    
          // Elements
          const confirmEl = template.content.querySelector('.confirm');
          const btnClose = template.content.querySelector('.confirm__close');
          const btnOk = template.content.querySelector('.confirm__button--ok');
          const btnCancel = template.content.querySelector('.confirm__button--cancel');
    
          confirmEl.addEventListener('click', e => {
              if (e.target === confirmEl) {
                  options.oncancel();
                  this._close(confirmEl);
              }
          });
    
          btnOk.addEventListener('click', () => {
              options.onok();
              this._close(confirmEl);
          });
    
          [btnCancel, btnClose].forEach(el => {
              el.addEventListener('click', () => {
                  options.oncancel();
                  this._close(confirmEl);
              });
          });
    
          document.body.appendChild(template.content);
      },
    
      _close (confirmEl) {
          confirmEl.classList.add('confirm--close');
    
          confirmEl.addEventListener('animationend', () => {
              document.body.removeChild(confirmEl);
          });
      }
    
    };
