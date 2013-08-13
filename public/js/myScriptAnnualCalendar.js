		// Tableau pour stocker les Jours de la Semaine . 0=> Dimanche , 6 => Samedi
			var jsemaine = new Array();
			jsemaine = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
			
			// Tableau pour stocker les Jours de la Semaine abréviés . 0=> D , S => 6
			var jSemaineMin = new Array();
			jSemaineMin = ['D', 'L', 'Ma', 'Me', 'J', 'V', 'S'];
			
			// Tableau pour stocker les Mois de l'année. 0 => Janvier , 11 => Décembre
			var mois = new Array();
			mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
			
			// Tableau pour stocker les mois de l'année abréviés . 0 => Jan , 6 => Déc
			var moisMin = new Array();
			moisMin = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Déc'];
            
			var jMois = new Array();

			
			/***
			 * @desc myCalendar fonction qui calcule le nombre jour , puis le nom du jour du mois
			 *  de la date passée en argument 
			 * 
			 *
			 *
			 * @param date
			 * @returns {___days0} : tableau associatif d[i] = nomjour: d[mm] : mois de @date d[yyy] année de @date
			 */
			
			var calendarOptions = {
				    "color": { // Couleur Case selon code congé ou type jour 
				        "AU": "#E6AE00",
				        "N": "#FFFFFF",
				        "WE": "#EDEDED", //"#327CCB",
				        "FE": "#A74143", //"#A74143",
				        "FECSM" : "#F38630",
				        "CP": "#14B694", //,#9ED9C7
				        "Q1": "#F3CD08",
				        "Q2": "#FFC48C", //#FF9F80
				        "P": "#CFF09E",
				        "EX": "#92FCF7",
				        "45": "#9270C2",
				        "R": "#A595C2", //#B6B3F2
				        "F": "#3B868A", // #FFBDD8
				        "AP": "#1A20BA",
				        "M": "#5D656C",
				        "DF": "#FAF4B1",
				        "AS": "#EB540A",
				        "PC": "#7AB317",


				        "Entete": "#EDEDED", // couleur entete calendrier
				        "stroke": "#EDEDED" // couleur si bordure 

				    },
				    "dimensions": { 

				        "svg": { // dimensions du conteneur calendrier
				            "w": 920,
				            "h": 706

				        },

				        "day": { //dimensions de la case jour
				            "w": 25,
				            "h": 20,
				            "round": 8,
				            "margin": 0,
				            "offset": 34
				        },
				        "conge": { //dimensions de la case conge
				            "w": 20,
				            "h": 20,
				            "round": 8,
				            "margin": 0,
				            "offset": 59
				        },

				        "month": { //dimensions de la case mois / ressources
				            "w": 150,
				            "h": 20,
				            "round": 8,
				            "margin": 4

				        }
				    }
				};		
			
			function myCalendar(year) {
				    // Tableau des jours qui sera retournée à la fin de la fonction .
				    var days = new Array();
				    var dates = new Array();
		
				    if (new Date(year, 1, 29)
				        .getMonth() == 1) // si  année bissextile (vérifie si le 29/02 de l'année courante existe)
				    {
				        jfev = 29;
				    } else {
				        jfev = 28;
				    }
				
				    jMois = ['31', jfev, '31', '30', '31', '30', '31', '31', '30', '31', '30', '31']; //Tableau Jour/Mois
				
		            // Tableau contenant les dates des débuts de mois à partir desquelles on va calculer les nom de jour du mois .
				    for(var m=1;m<=12;m++)
				    {
				    	dates[m] = new Date(year,m-1,1); //mois commence de 0 => janvier / 11 => décembre
				    }
				    
				    var jsem;
				    var mjsem = new Array();
				    
				    for (var m = 1; m<= mois.length; m ++)
				    {
				    	
				    	 dd = dates[m].getDate(); //Jour.
						 d = dates[m].getDay(); // Jour de la semaine.
						 mm = dates[m].getMonth(); // Mois 0 pour Janvier .
						 yyyy = dates[m].getFullYear(); // Année yyyy
				    	 x = dd % 7;
				        
				         /*Jour de la semaine (Semaine sur 7 on calcule les jours de la semaine à partir de la date du jour dand dd)
							             x entre 0 et 6*/
				    	
				    	 mjsem[m] = null;
				    	
				    for (var i = 1; i <= jMois[m-1]; i++) //Parcourir du 1 à la fin du mois courant.
				    {
				        for (var j = 0; j <= 6; j++) // Parcourir les jours de la semaine (7 jours de 0=>'dimanche' à 6=>'samedi')
				        {
				            if (i % 7 == (j + x) % 7) //i = 1 / j = 0 => i%7 = 1 et 0 +x % 6 = x => x = 1 (1er jour mois)
				            {
				                y = d + j; // y = d+j = (jour + offset(j) ; j = 0 => y = d )
				
				                y = y % 7; // y entre 0 et 6 jours de la semaine
				
				                jsem = jSemaineMin[y]; // On obtient le jours de la semaine du jour pointé en utilisant l'indice y(de 0 à 6) dans le tabealu jsemaine
				
				                break;
				
				            }
				
				        }
				     
				        days[i - 1] = jsem; // d['08'] = 'lundi'; d['09'] = 'mardi'; d['10'] = 'mercredi'; .. ..
				    }
				    days['mm'] = mm; // mois de la date passée en argument
				    days['yyyy'] = yyyy; // année de la date passée en argument
				    mjsem[m] = days;
				    days = new Array();
				    
				    };
				   
				    return mjsem;

            }// fin function myCalendar.
            
			
			function isExist(structure, date) {

			    // Calcul taille structure => nombre de conge par mois

			    congeCount = Object.keys(structure)
			        .length;

			    //tableau contenant pour chaque (indice N, un indice date)  => nombre d'indice double..


			    var data = {};

			    for (i = 0; i < congeCount; i++) {
			        if (typeof structure[i][date] !== "undefined") {

			            daysCount = Object.keys(structure[i])
			                .length;

			            daysCount = (daysCount - 1) / 2; // - nombreJour

			          
			            if (date == structure[i]['0']['Date'] || date == structure[i][daysCount - 1]['Date']) {

			                if (structure[i]['0']['Date'] == structure[i][daysCount - 1]['Date']) { // date debut == date fin
			                   
			                	if (structure[i]['0']['DebutMidi'] == 'false' && structure[i]['0']['FinMidi'] == 'false') {
			                  //       console.log(date +' => '+i+ ' dd == df | debutMidi : False, FinMidi false' );
			                        data[date] = {};
			                        data[date] = structure[i][date]['TypeConge'];

			                    } else if (structure[i]['0']['DebutMidi'] == true) {

			                      //    console.log(date +' => '+i+ 'dd == df | debutMidi : true, FinMidi false' );

			                        if (typeof data[date] == "undefined") {
			                            data[date] = {};
			                            data[date].dm = structure[i][date]['TypeConge'];
			                        } else if (data[date].fm == structure[i][date]['TypeConge'])
			                            data[date].j = structure[i][date]['TypeConge'];
			                        else if (data[date].fm !== structure[i][date]['TypeConge'])
			                            data[date].dm = structure[i][date]['TypeConge'];
			                       

			                    } else if (structure[i]['0']['FinMidi'] == true) {
			                     //     console.log(date +' => '+i+ 'dd == df | debutMidi : false, FinMidi true' );

			                        if (typeof data[date] == "undefined") {
			                            data[date] = {};
			                            data[date].fm = structure[i][date]['TypeConge'];
			                        } else if (data[date].dm == structure[i][date]['TypeConge'])
			                            data[date].j = structure[i][date]['TypeConge'];
			                        else if (data[date].dm !== structure[i][date]['TypeConge'])
			                            data[date].fm = structure[i][date]['TypeConge'];

			                       
			                    }
			                	//date == structure[i]['0']['Date'] && date !== structure[i][daysCount - 1]['Date']) || (date !== structure[i]['0']['Date'] && date == structure[i][daysCount - 1]['Date'])
			                	
			                } else if (structure[i]['0']['Date'] !== structure[i][daysCount - 1]['Date']) {
			                //	 console.log(data);

			                    if (structure[i]['0']['DebutMidi'] == true && date == structure[i]['0']['Date']) {
			                      //  	console.log(date +' => '+i+ 'dd !== df | debutMidi : true' );
			                    	
			                        if (typeof data[date] == "undefined") {
			                            data[date] = {};
			                            data[date].dm = structure[i][date]['TypeConge']; 
			                        } else if (data[date].fm && data[date].fm == structure[i][date]['TypeConge'])
			                            data[date].j = structure[i][date]['TypeConge'];
			                        else if (data[date].fm !== structure[i][date]['TypeConge'])
			                            data[date].dm = structure[i][date]['TypeConge'];
			                       
			                       
			                    } else if (structure[i]['0']['DebutMidi'] == false && date == structure[i]['0']['Date'] ) {
			                    	 
			                        data[date] = {};
			                        data[date].j = structure[i][date]['TypeConge'];
			                    	 

			                    }
			                    
			                    if (structure[i][daysCount - 1]['FinMidi'] == true && date == structure[i][daysCount - 1]['Date']) {
			                 //        console.log(date +' => '+i+ 'dd !== df | FinMidi : true' );

			                        if (typeof data[date] == "undefined") {
			                            data[date] = {};
			                            data[date].fm = structure[i][date]['TypeConge'];
			                        } else if (data[date].dm == structure[i][date]['TypeConge'])
			                            data[date].j = structure[i][date]['TypeConge'];
			                        else if (data[date].dm !== structure[i][date]['TypeConge'])
			                            data[date].fm = structure[i][date]['TypeConge'];


			                    } else if (structure[i][daysCount - 1]['FinMidi'] == false && date == structure[i][daysCount - 1]['Date']) {
			                        //	console.log(date +' => '+i+ 'dd !== df | FinMidi : false' );
			                        data[date] = {};
			                        data[date].j = structure[i][date]['TypeConge'];


			                    }

			                }

			            }
			        }

			    } // fin for


			    for (i = 0; i < congeCount; i++) {


			        if (typeof structure[i] !== "undefined") {
			            daysCount = Object.keys(structure[i])
			                .length;
			            daysCount = (daysCount - 1) / 2; //-1 pour indice NombreJour

			            if (typeof (data[date]) !== "undefined") {
			                if (data[date].j)
			                    return data[date].j;
			                else
			                	{
			                	console.log(date);
			                	console.log(data);
			                	return data;
			                	}
			                	
			                	
			            } else if (typeof structure[i][date] !== "undefined")

			                return structure[i][date]['TypeConge'];


			        }


			    }
			    
			    return;

			}

			
              
            /** 
			 * @desc function qui renvoie la concaténation de l'attribut x et y (coordonnées)
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
			 *          r1: degré pour arrondir le coin à gauche en haut
			 *          r2: degré pour arrondir le coin à droite en haut
			 *          r3: degré pour arrondir le coin à droite en bas
			 *          r4: degré pour arrondir le coin à gauche en bas
			 */
			
			function dPath(x, y, w, h, r1, r2, r3, r4) {
			    var d = 'M' + p(x + r1, y); //A
			    d += "L" + p(x + w - r2, y) + "Q" + p(x + w, y) + p(x + w, y + r2); //B
			    d += "L" + p(x + w, y + h - r3) + "Q" + p(x + w, y + h) + p(x + w - r3, y + h); //C
			    d += "L" + p(x + r4, y + h) + "Q" + p(x, y + h) + p(x, y + h - r4); //D
			    d += "L" + p(x, y + r1) + "Q" + p(x, y) + p(x + r1, y); //A
			
			    return d;
			}
            
    
        
         
      
         function DrawAnnualCalendar(year,opt,dataset)
         {
        	jQuery('.smoothie').remove();
             d3.selectAll('svg')
             .style("opacity", 1)
             .transition()
             .duration(400)
             .style("opacity", 0)
             .remove();
             jQuery('#wrapper').html('<span class="smoothie">'+year+'</span>');
        	 
          
        	 
        	 var dataj = myCalendar(year); 
        	 
        	  data = new Array();

              for (var i = 0; i < 12; i++) { // tableau de 0 à 11 mois de l'année; 0 =>Janvier ; 11 => Décembre
                      data[i] = i;
              };

              svgContainer = d3.select('#wrapper')
                      .append('svg')
                      .attr('width', '706')
                      .attr('height', '900');

              /*
               * On dessine ci-dessous la premiere colonne ,
               *  contenant l'année et les numéros de jours du mois
               *
               */
              for (var i = 0; i < 32; i++) {

                      svgContainer.append('path')
                      // ronds jours mois
                      .attr('fill', function () {
                              return '#ededed';
                      })
                              .attr('d', function () {
                                      dPathy = i * 10;
                                      return dPath(0, dPathy, 20, 20, 0, 0, 0, 0);

                              })
                              .transition()
                              .duration(1500)
                              .ease('cubic')
                              .attr('d', function () {
                            	    dPathy = i * 20;
                            	  if(i == 0)
                            		  {
                            		  return '';
                            		   //return dPath(0, 0, 20, 20,11, 11, 0, 11);
                            		  }
                                  
                                      return dPath(0, dPathy, 20, 20, 11, 11, 11, 11);
                              });

                      svgContainer.append("text")
                              .transition()
                              .delay(1600)
                              .duration(200) // texte n jours mois 
                             
                              .style('fill', '#413D3D')
                              .text(function () {
                                      if (i == 0) return '';

                                      i = i > 9 ? i : '0' + i;
                                      return i;
                              })
                              .attr('dx', function () {
                            	  
                                      return (5);
                              })
                              .attr('dy', function () {
                            	  
                            	
                                      return i * 20 + 12;
                              })
                              .style('font', function(){
                            	  if(i == 0)
                            		  {
                            		  return 'none';
                            		  }
                            	  return '10px  Tahoma,Lucida grande, Verdana, Arial, Sans-serif';}
                            	  );
                             


              }
           
              

              /*
  		       * 12 groupe contenant chacun :
  		       * l'entete mois + text
  		       * la colonne pour les bars vacs scolaire,
  		     
  		       */
              gr = svgContainer.selectAll('g')
                      .data(data) // data[1] == 1 à data[12] == 12  
              .enter()
                      .append('g');

              gr.append('rect') // entete mois
              .transition()
                      .delay(function (d, i) {
                              return (i + 1) * 200;
                      })
                      .attr('fill', 'none')
                      .attr('stroke', '#C2CBCE')
                      .attr('x', function (d, i) {
                              return i * 57 + 22;
                      })
                      .attr('y', 0)
                      .attr('width', 57)
                      .attr('height', 20);


              gr.append("text") // text mois
              .style('fill', '#413D3D')
                      .text(function (d, i) {
                              return mois[i];
                      })

              .attr('dx', function (d, i) {
                      return i * 57 + 27;
              })
                      .attr('dy', 12)
                      .style('font', '10px Lucida grande,Tahoma, Verdana, Arial, Sans-serif');

              gr.append('rect')
                      .transition()
                      .delay(function (d, i) {
                              return (i + 1) * 260;
                      })
                      .attr('fill', '#ffffff')
                      .attr('stroke', '#ededed')
                      .attr('x', function (d, i) {
                              return i * 57 + 22;
                      })
                      .attr('y', 20)
                      .attr('width', 12)
                      .attr('height', function(d,i){
                    	  return 20*jMois[i];
                      });
              
              
              
              
              /*
               * On Parse les données de vancances récupérées depuis le serveur
               * pour construire une structure "lisible" pour le dessin des bars vancances.
               * 
               */
              vacancesArray = new Array();
              
              jQuery.each(dataset.Vacances['0'],function(i,d){
          		console.log(i + '=>'+d['zone']+' '+ d['date_debut'] +' '+ d['date_fin']);
          		
          		monthD = d['date_debut'].split('-',3);
          		monthF = d['date_fin'].split('-',3);
          //		console.log(parseInt(monthD[1]) + '  '+ parseInt(monthF[1]));
          		
          		if(typeof vacancesArray[d['zone']] == 'undefined') 
  				{
  			    // Pas de tableau associatif en Javascript => solution :  tableau dans case tableau !
  				vacancesArray[d['zone']]=[];
  				}
          		
          		if(parseInt(monthD[1]) == parseInt(monthF[1]))
          			{
          			
          		
          			vacancesArray[d['zone']][parseInt(monthD[1])]=[];
      				vacancesArray[d['zone']][parseInt(monthD[1])]['from'] = parseInt(monthD[2]) ;
                    vacancesArray[d['zone']][parseInt(monthD[1])]['to'] = parseInt(monthF[2]) ;
                    
                 //   console.log( d['zone']+' '+parseInt(monthD[1])+'from '+vacancesArray[d['zone']][parseInt(monthD[1])]['from']+'To '+vacancesArray[d['zone']][parseInt(monthD[1])]['to']);
                 
          			
          			}
          		else
          			{
          		
          			difMois = parseInt(monthF[1])-parseInt(monthD[1]);
          			
          			for(i=0;i<=difMois;i++)
          				{
          		
          				if(i == 0)
          					{
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ] = [];   // Pas de tableau associatif en Javascript => solution :  tableau dans case tableau !
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['from'] = parseInt(monthD[2]); 
          					indiceMois = parseInt(monthD[1]) + (i-1);
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['to'] = jMois[''+indiceMois+''];
          					
          					}
          				
          				else if( i == difMois )
          					{
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ] = [];   // Pas de tableau associatif en Javascript => solution :  tableau dans case tableau !
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['from'] = 1;
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['to'] = parseInt(monthF[2]);
          					}
          				else
          					{
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ] = [];   // Pas de tableau associatif en Javascript => solution :  tableau dans case tableau !
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['from'] = 1;
          					indiceMois = parseInt(monthD[1]) + (i-1);
              				
          					vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['to'] = jMois[''+indiceMois+''];
          					
          					}
          			//	console.log(d['zone'] + ' ' + (parseInt(monthD[1]) + i) +'from : '+ vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['from'] + 'To : '+ vacancesArray[d['zone']][parseInt(monthD[1]) + i ]['to']);
          				
          				}
          			
          			
          		
          			
          			
          			
          			}
          		
          		
          	});
              
            

              //Barre vacances Zone A : 
              gr.append('rect') .attr('fill', '#F56991').attr('y',20).attr('height',function(d,i){return 20 + 20*jMois[i];})
                      .transition()
                      .delay(function (d, i) {
                              return (i + 1) * 300;
                      })
                      .duration(1500)
                      .ease('cubic')
                      .attr('fill', '#F56991')
                      .attr('x', function (d, i) {
                              return i * 57 + 22;
                      })
                      .attr('y',function(d,i){
                    	  if(typeof vacancesArray['A'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                    		  {
                    		  return 20;
                    		  }
                    	  if(typeof vacancesArray['A'][i+1] !== 'undefined')
                    		  {
                    		  return parseInt(vacancesArray['A'][i+1]['from']) * 20;
                    	      
                    		  }
                                                 return 20 ; })
                      .attr('width', 4)
                      .attr('height', function(d,i){
                    	  if(typeof vacancesArray['A'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                		  {
                		  return 0;
                		  }
                    	  
                    	  if(typeof vacancesArray['A'][i+1] !== 'undefined')
                		  {
                		  barLength = vacancesArray['A'][i+1]['to'] - vacancesArray['A'][i+1]['from'] ; // Nombre de jour de vacances à partir du jour du début déterminera la hauteur de la barre , (awkward name of var :))!
                		  return 20 + barLength * 20 ; // marge de 20 represente la taille du premier rectancgle du calendrier (l'entete mois)
                	      
                		  }
                    	  return 0;
                      });

            //Barre vacances Zone B : 
              gr.append('rect')   .attr('fill', '#F9CDAD').attr('y',20).attr('height',function(d,i){return 20 + 20*jMois[i];})
                      .transition()
                      .delay(function (d, i) {
                              return (i + 1) * 340;
                      }).duration(1500)
                      .ease('cubic')
                      .attr('fill', '#F9CDAD')
                      .attr('x', function (d, i) {
                              return i * 57 + 26;
                      })
                      .attr('y',function(d,i){
                    	  
                    	  if(typeof vacancesArray['B'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                		  {
                		  return 20;
                		  }
                    	  
                    	  if(typeof vacancesArray['B'][i+1] !== 'undefined')
                		  {
                		  return parseInt(vacancesArray['B'][i+1]['from']) * 20;
                	      
                		  }
                                             return 20 ; })
                      .attr('width', 4)
                      .attr('height', function(d,i){
                    	  if(typeof vacancesArray['B'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                		  {
                		  return 0;
                		  }
                    	  
                    	  if(typeof vacancesArray['B'][i+1] !== 'undefined')
                		  {
                		  barLength = vacancesArray['B'][i+1]['to'] - vacancesArray['B'][i+1]['from'] ; // Nombre de jour de vacances à partir du jour du début déterminera la hauteur de la barre , (awkward name of var :))!
                		  return 20 + barLength * 20 ; // marge de 20 represente la taille du premier rectancgle du calendrier (l'entete mois)
                	      
                		  }
                    	  return 0;
                      });


            //Barre vacances Zone C : 
              gr.append('rect')
                      .transition().attr('fill', '#EFFAB4').attr('y',20).attr('height',function(d,i){return 20 + 20*jMois[i];})
                      .delay(function (d, i) {
                              return (i + 1) * 380;
                      })
                      .duration(1500)
                      .ease('cubic')
                      .attr('fill', '#EFFAB4')
                      .attr('x', function (d, i) {
                              return i * 57 + 30;
                      })
                      .attr('y',function(d,i){
                    	  if(typeof vacancesArray['C'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                		  {
                		  return 20;
                		  }
                    	  
                    	  if(typeof vacancesArray['C'][i+1] !== 'undefined')
                		  {
                		  return parseInt(vacancesArray['C'][i+1]['from']) * 20;
                	      
                		  }
                                             return 20 ; })
                      .attr('width', 4)
                      .attr('height', function(d,i){
                    	  if(typeof vacancesArray['C'] == 'undefined') // Pas d'informations de vacances ou Pas de vancances en ce mois
                		  {
                		  return 0;
                		  }
                    	  
                    	  if(typeof vacancesArray['C'][i+1] !== 'undefined')
                		  {
                		  barLength = vacancesArray['C'][i+1]['to'] - vacancesArray['C'][i+1]['from'] ; // Nombre de jour de vacances à partir du jour du début déterminera la hauteur de la barre , (awkward name of var :))!
                		  return 20 + barLength * 20 ; // marge de 20 represente la taille du premier rectancgle du calendrier (l'entete mois)
                	      
                		  }
                    	  return 0;
                      });




              /*       
  			    ci-dessous on dessine      
  		       * la colonne pour les noms de jours de la semaine,
  		       * la colonne pour les congés .
           
              */



              for (var i = 0; i < mois.length; i++) {
                     
            	     month = i < 9 ? '0' + (i + 1) : month = i + 1;
            	     indiceConge = year+ '-' + month;
            	     
            	     dataLength = dataj[i + 1].length; 
            	     dataj[i + 1][dataLength] = 0; // on rajoute une case pour contenir le nombre jours .
            	     var jcount = 0; //Contient le nombre de ressources affichés si 0 toutes les ressources travaillent .
            	     var dataJson = [];
            	 jQuery.each(dataset.ressources, function (index, val) { // Parcourir ressource par ressource // Une seule ressource est retournée donc index à 0


            	            count = 0; // contiendra la somme des nombres jours congés sur le mois / ressources
            	            if (typeof dataset['ressources'][index]['conge'][indiceConge] !== 'undefined') {
            	                jcount++;
            	                jQuery.each(dataset['ressources'][index]['conge'][indiceConge], function (iConge, val) {

            	                    count += parseFloat(dataset['ressources'][index]['conge'][indiceConge][iConge]['nombreJours']);
            	                });
            	            }

            	             //   console.log('count => ' + jcount);
            	                dataj[i + 1][dataLength] = count;

            	                var iCs; // indice centre de service pour puiser dans la structure dans les jours fériés selon l'indice CS.
            	                if (dataset['ressources'][index]['cs'] == '0')
            	                    iCs = 'France';
            	                else
            	                    iCs = 'CSM';

            	              
            	                k = 0;
            
                    
                    	  jQuery.each(dataj[i+1], function (j, val) {
                    	  data = dataj[i+1];
                    	
                    	  if (j !== dataLength) {
                    		  
                              day = j < 9 ? '0' + (j + 1) : day = j + 1;
                              month = i < 9 ? '0' + (i + 1) : (i + 1);
                              var thisDate = periode.Year + "-" + month + "-" + day;


                              title = data[j] + ' ' + (j + 1) + ' ' + mois[data['mm']] + ' ' + periode.Year;
                             

                              typeConge = null;
                              thisDate = periode.Year + "-" + month + "-" + day;
                              //console.log(data[i] + '=>' + i);

                              if (data[j] == 'S') {
                                  dataJson[k] = {
                                      "date": thisDate,
                                      "typeJour": 'SA',
                                      "typeConge": typeConge,
                                      "title": title,
                                      "jour": j
                                  };
                                  k++;
                                  return true;
                              } else if (data[j] == 'D') {
                                  dataJson[k] = {
                                      "date": thisDate,
                                      "typeJour": 'DI',
                                      "typeConge": typeConge,
                                      "title": title,
                                      "jour": j
                                  };
                                  k++;
                                  return true;
                              }

                              if (dataset['Ferie'][iCs][thisDate]) {
                                  title = dataset['Ferie'][iCs][thisDate] + " : </br>" + title;

                                  dataJson[k] = {
                                      "date": thisDate,
                                      "typeJour": 'FE',
                                      "typeConge": typeConge,
                                      "title": title,
                                      "jour": j
                                  };
                                  k++;
                                  return true;
                              }

                             
                              tConge = null;
                              
                              if (typeof dataset['ressources'][index]['conge'][indiceConge] !== "undefined") {
                                  tConge = isExist(dataset['ressources'][index]['conge'][indiceConge], thisDate);
                               
                              }
                              
                              if(tConge == null)
                    		  {
                    		  dataJson[k] = {
                                      "date": thisDate,
                                      "typeJour": 'N',
                                      "typeConge": typeConge,
                                      "title": title,
                                      "jour": j
                                  };
                                  k++;
                                  return true;
                    		  }
                               
                              else if (typeof tConge !== 'undefined') {
                            	  
                            	 
                            	  
                            	 if (typeof tConge[thisDate] == 'undefined'  ) { // si date est prise en entier comme congé  
                                     // console.log(thisDate);
                                	  dataJson[k] = {
                                          "date": thisDate,
                                          "typeJour": 'C',
                                          "typeConge": tConge,
                                          "title": title,
                                          "jour": j
                                      };
                                      k++;
                                      return true;
                                  } else if (tConge[thisDate].dm && tConge[thisDate].fm) { //si deux demi journée de congé sur même jour ( de congé différent)

                                      dataJson[k] = {
                                          "date": thisDate,
                                          "typeJour": 'FM',
                                          "typeConge": tConge[thisDate].fm,
                                          "title": title,
                                          "jour": j
                                      };
                                      dataJson[k + 1] = {
                                          "date": thisDate,
                                          "typeJour": 'DM',
                                          "typeConge": tConge[thisDate].dm,
                                          "title": title,
                                          "jour": j
                                      };
                                      k = k + 2;
                                      return true;
                                  } else if (tConge[thisDate].dm && !tConge[thisDate].fm) { 
                                      dataJson[k] = {
                                          "date": thisDate,
                                          "typeJour": 'FM',
                                          "typeConge": 'NaN',
                                          "title": title,
                                          "jour": j
                                      };
                                      dataJson[k + 1] = {
                                          "date": thisDate,
                                          "typeJour": 'DM',
                                          "typeConge": tConge[thisDate].dm,
                                          "title": title,
                                          "jour": j
                                      };
                                      k = k + 2;
                                      return true;
                                  } else if (tConge[thisDate].fm && !tConge[thisDate].dm) {
                                      dataJson[k] = {
                                          "date": thisDate,
                                          "typeJour": 'FM',
                                          "typeConge": tConge[thisDate].fm,
                                          "title": title,
                                          "jour": j
                                      };
                                      dataJson[k + 1] = {
                                          "date": thisDate,
                                          "typeJour": 'DM',
                                          "typeConge": 'NaN',
                                          "title": title,
                                          "jour": j
                                      };
                                      k = k + 2;
                                      return true;
                                  }

                              } else {
                                
                              }


                          } else {

                              dataJson[k] = {
                                  "nombreJours": data[j],
                                  "indice": j
                              };
                              k++;
                          }
                    	  });
            	            
                         //console.log('dataJson ' + i);
                         //console.log(dataJson);
                    	  
                          for (var j = 0; j <= dataJson.length -1 ; j++) {
                              svgContainer.append('path')
                                      .transition()
                                      .delay((i + 1) * 250)
                                      .ease('cubic')
                                      .attr('fill', function(){
                                    	  
                                    	if ( dataj[i+1][dataJson[j].jour] == 'S' || dataj[i+1][dataJson[j].jour] == 'D')
                                    	  {
                            	             return opt.color.WE;
                                    	  }
                                        return 'none';
                                    	  
                                      })
                                      .attr('stroke', '#C2CBCE')
                                      .attr('d', function () {
                                    	  
                                    		  dPathy = dataJson[j].jour * 20;
                                    		
                                    		
                                    		  if(dataj[i+1][dataJson[j].jour] == 'S' || dataj[i+1][dataJson[j].jour] == 'D')
                                    			  {
                                    			  return '';
                                    			  }
                                    		  
                                    		  return dPath(57 * i + 34, dPathy + 20, 25, 20, 0, 0, 0, 0);
                                    	  
                                      });

                              svgContainer.append('path')
                                      .transition()
                                      .delay((i + 1) * 280)
                                      .ease('cubic')
                                      .attr('stroke', function(){
                                    	  
                                    	  
                                      	  if (dataJson[j].typeConge == null || dataJson[j].typeConge == 'NaN') {
                                      		  if(dataJson[j].typeJour == 'FE')
                                      			  {
                                      			  return '#FFFFFF';
                                      			  }
                                      		return '#C2CBCE';
                                      			  }
                                      	  else
                                      		   {return 'none';}
                                      })
                                      .attr('stroke-witdh', function(){
                                    	  
                                    	
                                    	  if (dataJson[j].typeConge == null) {return 1;}
                                    	  else
                                    		   {return 0;}
                                    	  
                                      })
                                      .attr('fill', function(){
                                    	  
                                    	  if (j == (dataJson.length - 1)) {
                                              return '#327CCB';
                                          }

                                          if (dataJson[j].typeConge == null) {

                                              if (dataJson[j].typeJour == 'FE') {
                                                  return opt.color.FE;
                                              } else if (dataJson[j].typeJour == 'N') {
                                                  return opt.color.N;
                                              }

                                          } else {
                                              if (dataJson[j].typeConge == 'NaN')
                                                  return opt.color.N;
                                              else
                                                  return opt.color[dataJson[j].typeConge];

                                          }

                                          return opt.color.N;
                                    	  
                                      })
                                  
                                      .attr('d', function () {
                                    	  
                                    	  d= dataJson[j];
                                    	
                                    	// form 'd' pour jour normal
                                       
                                         
                                    	  dPathx = 57 * i;
                                    	  dPathy = d.jour * 20;
                                          
                                    	  
                                    	  
                                    	  
                                          if (j == (dataJson.length - 1)) {
                                        	  
                                              dPathy = d.indice *20;
                                              return dPath(dPathx + opt.dimensions.conge.offset,  dPathy +20 , 20, opt.dimensions.day.h, 0, 0, 5, 5);
                                          }
                                         

                                          if (d.typeConge == null) {

                                              if (d.typeJour == 'SA' || d.typeJour == 'DI') {
                                                  return dPath(57 * i + 34, dPathy + 20, 45, 20, 0, 0, 0, 0);}
                                              else if (d.typeJour == 'FE') {
                                                  return dPath(dPathx + opt.dimensions.conge.offset, dPathy + 20, opt.dimensions.conge.w, opt.dimensions.conge.h, 0, 0, 0, 0);
                                              } else if (d.typeJour == 'N') {
                                                  return dPath(dPathx + opt.dimensions.conge.offset, dPathy + 20, opt.dimensions.conge.w, opt.dimensions.conge.h, 0, 0, 0, 0);
                                              }

                                          } else {
                                        	  if (d.typeJour == 'C') {
                                                  return dPath(dPathx + opt.dimensions.conge.offset +1, dPathy + 21 , opt.dimensions.conge.w -2 , opt.dimensions.conge.h -2, 5, 5, 5, 5);
                                              }
                                              if (d.typeJour == 'FM' && d.typeConge == 'NaN') {
                                                 // var dPathxdm = (d.jour * (9.5 + opt.dimensions.conge.margin)) + opt.dimensions.conge.offset;
                                                  var dPathydm = (d.jour * (20 + opt.dimensions.conge.margin) + 20)
                                                  return dPath(dPathx + opt.dimensions.conge.offset, dPathydm ,20, 9.5, 0, 0, 0, 0);
                                              } else if (d.typeJour == 'FM') {
                                               
                                                  var dPathydm = (d.jour * (20 + opt.dimensions.conge.margin) + 20)
                                                  return dPath(dPathx + opt.dimensions.conge.offset + 1, dPathydm  ,18, 9.5, 0, 0, 5, 5);

                                              }
                                              if (d.typeJour == 'DM' && d.typeConge == 'NaN') {
                                                  var dPathydm = (d.jour * (20 + opt.dimensions.conge.margin)) + 10 + 20;
                                                  return dPath(dPathx + opt.dimensions.conge.offset, dPathydm  , 20, 9.5, 0, 0, 0, 0);
                                              } else if (d.typeJour == 'DM') {
                                                  var dPathydm = (d.jour * (20 + opt.dimensions.conge.margin)) + 10 + 20;
                                                  return dPath(dPathx + opt.dimensions.conge.offset + 1, dPathydm  , 18, 9.5, 5, 5, 0, 0);
                                              }
                                              

                                          }
                                    	  
                                    	  
                                      
                                    	  
                                              
                                             // return dPath(57 * i + 59, dPathy + 20, 20, 20, 0, 0, 0, 0);
                                      });

                          
                            
                             svgContainer.append("text")
                              .transition()
                              .delay((i + 1) * 560)
                              .ease('elastic')
                              .style('fill', function(){
                            	  
                            	  if (j == (dataJson.length - 1)) {
                                      return '#FFFFFF';
                                  } else
                                  if (dataJson[j].typeConge !== null) {
                                	 
                                	   return '#4B000F';
                                  }
                            	  if(d.typeJour =='SA' || d.typeJour =='DI')
                            		  return '#413D3D';
                                  return '#FFFFFF';
                              })
                              
                              .text(function () {
                            	  if(j == dataJson.length -1)
                            		  {
                            		  return dataJson[j].nombreJours;
                            		  }
                            	 
                            	  if (dataJson[j].typeConge == null) {
                            		  
                            		  if(dataJson[j].typeJour == 'FE' || dataJson[j].typeJour =='SA' || dataJson[j].typeJour == 'DI')
                            			  {
                            			  return dataJson[j].typeJour;
                            			  }
                            		  else if(dataJson[j].typeJour =='N'){
                            			  return ''; // nom jour déjà affiché sur le path jour.
                            		  }
                            		  else  {
                                          return dataJson[j].typeJour;
                            		  }


                                  } else {
                                      if (dataJson[j].typeConge == 'NaN')
                                          return '';
                                      else
                                          return dataJson[j].typeConge;

                                  }
                            	  
                            	  return dataJson[j].typeJour;
                            	  
                              })
                              .duration(500)
                              .attr('x', function(){
                            	  if( d.typeJour == 'SA' || d.typeJour == 'DI')
                     			 {
                     			 return i * 57 + 37; 
                     			 }
                            	  return   i * 57 + 63;
                              }
                            )
                              .attr('y', function () {
                            	  if(j !== dataJson.length -1)
                            		  {
	                            		  if (dataJson[j].typeConge !== null) {
	                                    	  
	                            			  if (d.typeJour == 'DM') {
	                                              return dataJson[j].jour * 20 + 39;
	                                          }
	                            			  else if(d.typeJour == 'FM')
	                            				{
	                            				  return dataJson[j].jour * 20 + 29;
	                            				}  
	                            			  else
	                            				  {
	                            				  return dataJson[j].jour * 20 + 30;
	                            				  }
	                                	  }
	                            		  else
	                            			 {
	                            			  return dataJson[j].jour * 20 + 30;
	                            			 }
                            		  }
                            	  else
                            		  {
                            		 
                            		    return dataJson[j].indice * 20 + 30; 
                            		  }
                            		 
                            	 
                            	
                              })
                              .style('font', '10px  Tahoma,Lucida grande, Verdana, Arial, Sans-serif');
                             
                             svgContainer.append("text")
                             .transition()
                             .delay((i + 1) * 360)
                             .ease('elastic')
                             .style('fill', '#413D3D')
                             .text(function () {
                           	  if(j == dataj[i+1].length -1)
                           		  {
                                     return 'NJ';}
                           	  else
                           		  if(dataj[i+1][j] == 'S' || dataj[i+1][j] == 'D')
                           			  {
                           			  // on n'affiche pas les nom jours des weekend , ils seront affichés aprés sur le path congé.
                           			  return '';
                           			  }
                           		  
                                     return dataj[i+1][j];
                             })
                             .duration(500)
                             .attr('dx',function(){if( j < jMois[i]) return  i * 57 + 37;})
                             .attr('dy', function () {
                            	
                            	 if( j < jMois[i])
                                     return j * 20 + 33;
                             })
                             .style('font', '10px  Tahoma,Lucida grande, Verdana, Arial, Sans-serif');

          
                      }
                   
            	        
            	        });
            	           
              } // end for

              
        	 
         }
         
         function getAnnualCalendarContent(idPersonne,annee)
         {
        	 
        	 if(idPersonne == 'x')
        		 {
        		 
        		 d3.selectAll('svg')
	             .style("opacity", 1)
	             .transition()
	             .duration(400)
	             .style("opacity", 0)
	             .remove();
	    		 
        		 
        		 jQuery('#wrapper>.hero-unit').remove();
        		 jQuery('#wrapper')
                 .append('<div class="hero-unit" ><h1>Aucune ressource sélectionnée</h1><p>Choississez une ressource puis l`année référence et cliquez sur charger le calendrier.</p></div>');
             	return;
        		 }
        	 
        	 jQuery.ajax({
        	        type: 'POST',
        	        async: false,
        	        url: './annuel',
        	        dataType: 'json', // échange en format JSON
        	        data: {
        	            annee: annee,
        	            id_personne: idPersonne
        	              },
        	        success: function (data, status) {

        	            // Période qui sera affiché dans le calendrier.
        	            periode = {
        	                "Year": parseInt(annee)
        	             };
        	            
        	            jQuery('.hero-unit')
        	            .remove();
        	            
        	            
        	            if(data == null)
        	            	{
        				    	
        				    		// $('#myModal')
        				             // .modal('hide');
        				    		 d3.selectAll('svg')
        				             .style("opacity", 1)
        				             .transition()
        				             .duration(400)
        				             .style("opacity", 0)
        				             .remove();
        				    		 
        				        	  jQuery('#wrapper')
        				              .append('<div class="hero-unit" ><h1>Pas de congés</h1><p>'+ jQuery('#personne option[value=' + idPersonne + '] ').text() + ' n\'a pas de congé prévu cet année .</p></div>');
        				        	
        				    		
        	            	}
        	            else
        	            	{
        	            
        	            	
        	            	
	        	            	// on dessine le calendrier
	        	              	 DrawAnnualCalendar(periode.Year,calendarOptions,data);
        	            	
        	            	}
        	            // setTimeout(function(data){calendarData = null;alert('Finito'+ calendarData);},60000);
        	     //       $('#myModal')
        	       //         .modal('hide');


        	        },

        	        complete: function (data) {
        	        	
        	            calendarData = JSON.parse(data.response);
        	           

        	        },
        	        error: function () {
        	            return;
        	        }


        	    });
           return calendarData;
        	 
         }
         
         
     	$(document).ready(function () {

          var calendarData = null ;
          
          date = new Date();
          annee = date.getFullYear();
       
          
          if(jQuery('#personne').val() == 'x')
        	  {
        	  jQuery('#wrapper')
              .append('<div class="hero-unit" ><h1>Aucune ressource sélectionnée</h1><p>Choississez une ressource puis l`année référence et cliquez sur charger le calendrier.</p></div>');
        	
        	  }
         
          

          jQuery('#chargerCalendrier')
          .unbind('click')
          .bind('click', function () {

              // récupération des valeurs entrées dans le formulaire
              idPersonne = jQuery('#personne')
                  .val();
              annee = jQuery('#annee')
                  .val();
             
              calendarData = getAnnualCalendarContent(idPersonne, annee);
            
             
             
             
             
          });


    });
		
		