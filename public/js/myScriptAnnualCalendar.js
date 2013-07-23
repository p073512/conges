
			
			// Tableau pour stocker les Jours de la Semaine . 0=> Dimanche , 6 => Samedi
			var jsemaine = new Array();
			jsemaine = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
			
			// Tableau pour stocker les Jours de la Semaine abr�vi�s . 0=> D , S => 6
			var jSemaineMin = new Array();
			jSemaineMin = ['D', 'L', 'Ma', 'Me', 'J', 'V', 'S'];
			
			// Tableau pour stocker les Mois de l'ann�e. 0 => Janvier , 11 => D�cembre
			var mois = new Array();
			mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
			
			// Tableau pour stocker les mois de l'ann�e abr�vi�s . 0 => Jan , 6 => D�c
			var moisMin = new Array();
			moisMin = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Déc'];


			
			/***
			 * @desc myCalendar fonction qui calcule le nombre jour , puis le nom du jour du mois
			 *  de la date pass�e en argument 
			 * 
			 *
			 *
			 * @param date
			 * @returns {___days0} : tableau associatif d[i] = nomjour: d[mm] : mois de @date d[yyy] ann�e de @date
			 */
			
			function myCalendar(date) {
				    // Tableau des jours qui sera retourn�e � la fin de la fonction .
				    var days = new Array();
				
				    // R�cup�ration de la date du jour .
				    today = new Date();
				
				    // on d�coupe la date en jours de la semaine / jours du mois / mois de l'ann�e / ann�e.
				    td = today.getDay();
				    tdd = today.getDate();
				    tmm = today.getMonth();
				    tyyyy = today.getFullYear();
				
				    // d�coupage (parsing) de la date en entr�e
				    dd = date.getDate(); //Jour.
				    d = date.getDay(); // Jour de la semaine.
				    mm = date.getMonth(); // Mois 0 pour Janvier .
				    yyyy = date.getFullYear(); // Ann�e yyyy
				
				
				
				    if (new Date(yyyy, 1, 29)
				        .getMonth() == 1) // si  ann�e bissextile (v�rifie si le 29/02 de l'ann�e courante existe)
				    {
				        jfev = 29;
				    } else {
				        jfev = 28;
				    }
				
				    var jMois = ['31', jfev, '31', '30', '31', '30', '31', '31', '30', '31', '30', '31']; //Tableau Jour/Mois
				
				
				    x = dd % 7;
				    /*Jour de la semaine (Semaine sur 7 on calcule les jours de la semaine � partir de la date du jour dand dd)
							             x entre 0 et 6*/
				
				    var jsem;
				    for (var i = 1; i <= jMois[mm]; i++) //Parcourir du 1 � la fin du mois courant.
				    {
				        for (var j = 0; j <= 6; j++) // Parcourir les jours de la semaine (7 jours de 0=>'dimanche' � 6=>'samedi')
				        {
				            if (i % 7 == (j + x) % 7) //i = 1 / j = 0 => i%7 = 1 et 0 +x % 6 = x => x = 1 (1er jour mois)
				            {
				                y = d + j; // y = d+j = (jour + offset(j) ; j = 0 => y = d )
				
				                y = y % 7; // y entre 0 et 6 jours de la semaine
				
				                jsem = jSemaineMin[y]; // On obtient le jours de la semaine du jour point� en utilisant l'indice y(de 0 � 6) dans le tabealu jsemaine
				
				                break;
				
				            }
				
				        }
				
				        days[i - 1] = jsem; // d['08'] = 'lundi'; d['09'] = 'mardi'; d['10'] = 'mercredi'; .. ..
				    };
				    days['mm'] = mm; // mois de la date pass�e en argument
				    days['yyyy'] = yyyy; // ann�e de la date pass�e en argument
				    return days;

            }// fin function myCalendar.
            
            
            /** 
			 * @desc function qui renvoie la concat�nation de l'attribut x et y (coordonn�es)
			 * utile pour le dessin du path dans dPath
			 *
			 * @param x
			 * @param y
			 * @returns {String}
			 *
			
			 */
			
			function p(x, y) {
			    return x + " " + y + " ";
			}


			/** fonction qui renvoie l'attribut d en fonction des params pour dessiner le path(chemin)
			 * path sous forme de rectancle avec des coins arrondis.
			 *  @param  x : position sur l'axe des X
			 *          y : position sur l'axe des Y
			 *          w : largeur
			 *          h : hauteur
			 *          r1: degr� pour arrondir le coin � gauche en haut
			 *          r2: degr� pour arrondir le coin � droite en haut
			 *          r3: degr� pour arrondir le coin � droite en bas
			 *          r4: degr� pour arrondir le coin � gauche en bas
			 */
			
			function dPath(x, y, w, h, r1, r2, r3, r4) {
			    var d = 'M' + p(x + r1, y); //A
			    d += "L" + p(x + w - r2, y) + "Q" + p(x + w, y) + p(x + w, y + r2); //B
			    d += "L" + p(x + w, y + h - r3) + "Q" + p(x + w, y + h) + p(x + w - r3, y + h); //C
			    d += "L" + p(x + r4, y + h) + "Q" + p(x, y + h) + p(x, y + h - r4); //D
			    d += "L" + p(x, y + r1) + "Q" + p(x, y) + p(x + r1, y); //A
			
			    return d;
			}

			$(document).ready(function(){
				
			
				date = new Date();
				data = new Array();
				
			    for (var i=1; i < 32; i++) { // tableau de 1 � 31 (jours du mois)
			    	data[i] = i;
				};
				
				
				  svgContainer = d3.select('#wrapper')
			        .append('svg')
			        .attr('width', '900')
			        .attr('height', '900');
			        
			        
			      gr = svgContainer.selectAll('g')
				                .data(data) // data contenant les informations pour l'affichage du calendrier (voir myCalendar())
				             .enter()
				          .append('g');
				          
				          
			      gr.append('path') // ronds jours mois
			          .attr('fill',function(d,i){return '#ededed'})
			           .attr('d',function(d,i){
			           	dPathy = i*10 ;
			           	return dPath(0,dPathy,20,20,0,0,0,0);
			           	
			           }).transition().duration(1500).ease('cubic').attr('d',function(d,i)
			           {dPathy = i*20 ;
			           	return dPath(0,dPathy,20,20,11,11,11,11);})  ;

              gr.append("text").transition().delay(1600).duration(200) // texte n jours mois 
               .style('fill', 'black')
                 .text(function (d, i) {
                 	if(i == 0) return '2013';
                 	           
                 	            i = i > 9 ? i  : '0' + i;
					            return i;
       							 })
		        .attr('dx', function (d, i) {
		         return (5);
		        }).attr('dy', function(d,i){ return i*20 + 10;})
			        .style('font', '8px sans-serif');
             
                 var svg = d3.select('#wrapper')
                    .append('svg')
                    .attr('width', 900)
                    .attr('height', 500);

                 
                 for (var i=0; i < mois.length; i++) { // de 0 (janvier) � 11 (d�cembre) 
                  
                  gr.append('path') // entete mois
                  .transition().delay(1800) 
                       .attr('fill','#ededed').attr('stroke','gray')
                         .attr('d',function() {
                         	
                         	dPathx = i*57;
                         	return dPath(dPathx +22, 0, 57,20,0,0,0,0);
                         	
                         });
               gr.append("text") // text mois
               .style('fill', 'black')
                 .text(mois[i])
            
		        .attr('dx',i*57 + 27).attr('dy', 10)
			        .style('font', '8px sans-serif');
                         
                  gr.append('path').transition().delay(2000) 
                      .attr('fill','#ffffff').attr('stroke','gray')
                       .attr('d',function() {
                       	
                       	dPathx = i*57;
                       	return dPath(dPathx + 22,20,12,620,0,0,0,0);
                       });
                       
                 gr.append('path').transition().delay(2200).ease('cubic')  
                  .attr('fill','blue')
                   .attr('d',function() {
                   	
                   	dPathx = i*57;
                   	return dPath(dPathx + 22,20,4,250,0,0,0,0);
                   });
                   
                   gr.append('path').transition().delay(2300).ease('cubic')  
                  .attr('fill','red')
                   .attr('d',function() {
                   	
                   	dPathx = i*57;
                   	return dPath(dPathx + 26,80,4,180,0,0,0,0);
                   });
                   
                 gr.append('path').transition().delay(2500).ease('cubic') 
                  .attr('fill','green')
                    .attr('d',function() {
                   	
			                   	dPathx = i*57;
			                   	return dPath(dPathx + 30,110,4,520,0,0,0,0);
               						    });
                       
                        dateCourante = new Date(2013, i);
                        dataj  = myCalendar(dateCourante);
                       
                  gr.selectAll('g').data(dataj).enter().append('path').transition().delay(2600).ease('cubic') 
                      	.attr('fill','none').attr('stroke','gray')
                    	   .attr('d',function(d,j){
		                       		 dPathy = j*20 ;
					             	return dPath(57*i+34,dPathy+20,25,20,0,0,0,0);
                       });
                       
                   gr.selectAll('g').data(dataj).enter().append('path').transition().delay(2800).ease('cubic') 
                  	.attr('fill','#ffffff').attr('stroke','gray')
                	   .attr('d',function(d,j){
	                       		 dPathy = j*20 ;
				             	return dPath(57*i+59,dPathy+20,20,20,0,0,0,0);
                   });
                  
                    gr.append("text")
		               .style('fill', 'black')
		                 .text(function(d,j){return dataj[j];})
				        .attr('dx',i*57 + 36).attr('dy', function(d,j){ return j*20 + 30;})
					        .style('font', '8px sans-serif');
                  
                   
                 } // end for
				
			
			});
			
