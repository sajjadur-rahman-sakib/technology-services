let header = document.querySelector('.header');
let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');


menu.onclick = () => {
  menu.classList.toggle('fa-times');
  navbar.classList.toggle('active');
}


window.onscroll = () =>{

  menu.classList.remove('fa-times');
  navbar.classList.remove('active');

  if(window.scrollY > 0){
    header.classList.add('active');
  }
  else{
    header.classList.remove('active');
  }

}


$(document).ready(function(){
  $('.count').each(function(){
    $(this).prop('Counter',0).animate({
      Counter: $(this).text()
    },
    {
      duration: 3500,
      easing:'swing',
      step: function (now){
        $(this).text(Math.ceil(now) + '+')
      }
    })
  });
});


let accordion = document.querySelectorAll('.accordion-container .accordion');

accordion.forEach(acco =>{
  acco.onclick = () =>{
    accordion.forEach(subAcco => { subAcco.classList.remove('active')});
    acco.classList.add('active');
  }
})


var swiper = new Swiper(".review-slider",{
  pagination:{
    el: ".swiper-pagination",
    clickable:true,
  },

  loop:true,
  spaceBetween: 20,
  grabCursor:true,
  autoplay: {
    delay: 7500,
    disableOnInteraction: false,
  },

  centeredSlides: true,
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    991: {
      slidesPerView: 3,
    },
  },
});