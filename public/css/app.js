/* -----------------------------------------------
/* How to use? : Check the GitHub README
/* ----------------------------------------------- */

/* To load a config file (particles.json) you need to host this demo (MAMP/WAMP/local)... */
/*
particlesJS.load('particles-js', 'particles.json', function() {
  console.log('particles.js loaded - callback');
});
*/

/* Otherwise just put the config content (json): */

if (document.getElementById('particles-js')) {
    
    particlesJS('particles-js',
        {
          "particles": {
            "number": {
              "value": 71,
              "density": {
                "enable": true,
                "value_area": 900
              }
            },
            "color": {
              "value": "#c5c5c5"
            },
            "shape": {
              "type": "circle",
              "stroke": {
                "width": 0,
                "color": "#2d292c"
              },
              "polygon": {
                "nb_sides": 4
              }
            },
            "opacity": {
              "value": 0.49716301422833176,
              "random": true,
              "anim": {
                "enable": false,
                "speed": 0.3996003996003996,
                "opacity_min": 0.17582417582417584,
                "sync": false
              }
            },
            "size": {
              "value": 2,
              "random": false,
              "anim": {
                "enable": false,
                "speed": 4.795204795204795,
                "size_min": 0.1,
                "sync": false
              }
            },
            "line_linked": {
              "enable": true,
              "distance": 126,
              "color": "#484d52",
              "opacity": 0.2966312312601217,
              "width": 1.5
            },
            "move": {
              "enable": true,
              "speed": 3,
              "direction": "none",
              "random": true,
              "straight": false,
              "out_mode": "out",
              "bounce": false,
              "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
              }
            }
          },
          "interactivity": {
            "detect_on": "canvas",
            "events": {
              "onhover": {
                "enable": false,
                "mode": "grab"
              },
              "onclick": {
                "enable": true,
                "mode": "push"
              },
              "resize": true
            },
            "modes": {
              "grab": {
                "distance": 400,
                "line_linked": {
                  "opacity": 1
                }
              },
              "bubble": {
                "distance": 85.26810729164123,
                "size": 12.181158184520177,
                "duration": 2,
                "opacity": 0.21114007519834974,
                "speed": 3
              },
              "repulse": {
                "distance": 146.17389821424212,
                "duration": 0.4
              },
              "push": {
                "particles_nb": 4
              },
              "remove": {
                "particles_nb": 2
              }
            }
          },
          "retina_detect": true
        }
    );
}
