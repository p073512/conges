// Tableau pour stocker les Jours de la Semaine .
 		var jsemaine = new Array();
 		jsemaine = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

 		 // Tableau pour stocker les Jours de la Semaine abréviés .
 		var jSemaineMin = new Array();
 		jSemaineMin = ['D', 'L', 'Ma', 'Me', 'J', 'V', 'S'];

 		 // Tableau pour stocker les Mois de l'année.
 		var mois = new Array();
 		mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

 		 // Tableau pour stocker les mois de l'année abréviés .
 		var moisMin = new Array();
 		moisMin = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Déc'];


 		/**
 		 * Tableau en format JSON pour contenir les options du calendrier.
 		 * 
 		 * Les options du calendrier touche surtout son aspect visuel : 
 		 * palette couleur , taille , espacement..
 		 * 
 		 * 
 		 */
 		var calendarOptions = {
 		    "color": {
 		        "N": "#EDEDED",
 		        "WE": "#D1D1D1", //"#327CCB",
 		        "FE": "#B2655D", //"#A74143",
 		        "CP": "#14B694", //,#9ED9C7
 		        "Entete": "#EDEDED",
 		        "stroke": "#EDEDED"

 		    },
 		    "dimensions": {

 		        "svg": {
 		            "w": 900,
 		            "h": 20

 		        },

 		        "day": {
 		            "w": 20,
 		            "h": 20,
 		            "round": 8,
 		            "margin": 2,
 		            "offset": 105
 		        },

 		        "month": {
 		            "w": 100,
 		            "h": 20,
 		            "round": 8,
 		            "margin": 4

 		        }
 		    }
 		};


 		/***
 		 * 
 		 * 
 		 * 
 		 * 
 		 * 
 		 * 
 		 * 
 		 * 
 		 * @param date
 		 * @returns {___days0}
 		 */



 		function myCalendar(date) {
 		    // Tableau des jours qui sera retournée à la fin de la fonction .
 		    var days = new Array();

 		    // Récupération de la date du jour .
 		    today = new Date();

 		    // on découpe la date en jours de la semaine / jours du mois / mois de l'année / année.
 		    td = today.getDay();
 		    tdd = today.getDate();
 		    tmm = today.getMonth();
 		    tyyyy = today.getFullYear();

 		    // découpage (parsing) de la date en entrée
 		    dd = date.getDate(); //Jour.
 		    d = date.getDay(); // Jour de la semaine.
 		    mm = date.getMonth(); // Mois 0 pour Janvier .
 		    yyyy = date.getFullYear(); // Année yyyy



 		    if (new Date(yyyy, 1, 29).getMonth() == 1) // si  année bissextile (vérifie si le 29/02 de l'année courante existe)
 		    {
 		        jfev = 29;
 		    } else {
 		        jfev = 28;
 		    }

 		    var jMois = ['31', jfev, '31', '30', '31', '30', '31', '31', '30', '31', '30', '31']; //Tableau Jour/Mois


 		    x = dd % 7;
 		    /*Jour de la semaine (Semaine sur 7 on calcule les jours de la semaine à partir de la date du jour dand dd)
			             x entre 0 et 6*/

 		    var jsem;
 		    for (var i = 1; i <= jMois[mm]; i++) //Parcourir du 1 à la fin du mois courant.
 		    {
 		        for (var j = 0; j <= 6; j++) // Parcourir les jours de la semaine (7 jours de 0=>'dimanche' à 6=>'samedi')
 		        {
 		            if (i % 7 == (j + x) % 7) //i = 1 / j = 0 => i%7 = 1 et 0 +x % 6 = x => x = 1 (1er jour mois)
 		            {
 		                y = d + j; // y = d+j = (jour + offset(j) ; j = 0 => y = d )

 		                y = y % 7; // y entre 0 et 6 jours de la semaine

 		                jsem = jsemaine[y]; // On obtient le jours de la semaine du jour pointé en utilisant l'indice y(de 0 à 6) dans le tabealu jsemaine

 		                break;

 		            }

 		        }

 		        days[i - 1] = jsem; // d['08'] = 'lundi'; d['09'] = 'mardi'; d['10'] = 'mercredi'; .. ..
 		    };
 		    days['mm'] = mm;
 		    days['yyyy'] = yyyy;
 		    return days;

 		}
        /**
         * 
         * 
         * @param x
         * @param y
         * @returns {String}
         */
 		
 		function isExist(structure,indice,value)
 		{
 			congeCount = Object.keys(structure).length;
 			console.log(congeCount +' is  congeCount');
 			if(congeCount%2 == 0) 
 				congeCount = congeCount/2;
 			else 
 				congeCount = (congeCount + 1) /2 ;
 			// tableau contenant pour chaque indice 0 un indice date ..
 		
 			for(i = 0;i<= congeCount;i++)
 				{
 				if(typeof structure[i] != 'undefined')
 					{
 					 congeDays = Object.keys(structure[i]).length;
 					}
 				else
 					{
 					break;
 					}
 				 
 				  if(congeDays%2 == 0) 
 					 congeDays = congeDays/2;
 		 			else 
 		 				congeDays = (congeDays + 1) /2 ;
 			
 				for (j = 0;j<congeDays; ++j )
 					{
 				
 	 				 if(structure[i][j]['Date'] == value)
 	 					 {
 	 					   return true; // sort de la boucle une fois la valeur trouvée (un return sort de la fonction carrement).
 	 					 }
 					}
 				 
 				}
 			return false;
 		}

 		/** 
 		 * function qui renvoie la concaténation de l'attribut x et y (coordonnées)
 		 * utile pour le dessin du path dans dPath
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


 		/**
		 * @param dataset : tableau contenant le détails de la période de congé.
		 *        j : l'indice du mois que l'on souhaite afficher.
		 *        opt : option d'affichage du calendrier (couleur, form , espacement)
		 *        ferie : tableau contenant les jours férié . 
		 
		 * @description :  fonction qui dessine un calendrier sous format svg 
		 *                 ce calendrier regroupe et distingue visuellement les différents type du jour du mois 
		 *                 (jour ordinaire[carre gris], jour de congé[carre au coins arrondis vert], jour weenkend , jour férié....)
		 * 
		 * 
		 */


 		function DrawMonthCalendar(periode, opt, dataset) {




 		    if (periode.To == periode.From) // Calendrier d'un seul mois
 		    {
 		        periode.To = periode.To + 1; // Période sur un mois du 01/mm/yyyy(inclus) au 01/mm+1/yyyy(non inclus)
 		        dateCourante = new Date(periode.Year, periode.From);
 		        var data = myCalendar(dateCourante);


 		    } else // Calendrier sur une période de l'année 'du (from) au (to)' 
 		    {
 		        debutAnnee = new Date(periode.Year, 0);
 		        var data = myCalendar(debutAnnee);
 		    }




 		    /*
 		     * Entête du calendrier , jour du mois numéroté sur deux caractéres 01,02,03 ...
 		     * 
 		     * Si période == 1 mois => jour du mois que l'on souhaite affiché.
 		     * Si période > 1 mois => jour du mois de 01 à 31.
 		     * 
 		     */

 		    gr = svgContainer.selectAll('g')
 		        .data(data) // data contenant les informations pour l'affichage du calendrier (voir myCalendar())
 		    .enter()
 		        .append('g'); // Ajout de balise <g> (pour regroupement) pour chaque item du data[]


 		    gr.append('path') // dans chaque tag <g> (précédemment ajouté) on ajout une balise path(chemin) sous forme de cercle
 		    .attr('stroke', '#ededed') // couleur bordure
 		    .attr('stroke-width', '1px') // taille bordure
 		    .attr('fill', '#ededed') // couleur de fond
 		    .attr('d', function (d, i) {
 		            /* 'd' avant transition
 		             * l'attribut d de <path> défini la forme et l'emplacement du <path>
 		             * L'idée est de chevaucher les cercles et de les replacer correctement
 		             * aprés transition.  'd' calculé en fonction de i.
 		             */

 		            var dPathx = (i * 15);
 		            return dPath(dPathx + 105, 0, 20, 20, 11, 11, 11, 11);
 		        })

 		    .transition() // transition ou animation 
 		    .delay(500) // déclenchement de l'animation aprés 500 ms
 		    .duration(1000) // durée de l'animation
 		    .ease("cubic") // effet de la transition (voir doc d3js)

 		    .attr('d', function (d, i) {
 		            /*
 		             * aprés transition
 		             * l'attribut d est resseté pour prendre les coordonnées
 		             * de l'emplacement correcte des cercles jours de l'entête
 		             *     
 		             */

 		            var dPathx = (i * 22);
 		            return dPath(dPathx + 105, 0, 20, 20, 11, 11, 11, 11);
 		        });


 		    /*
 		     * le text affiché sur les cercles notamment le numéro du jour 
 		     * l'alimentation des propriété de <text> va dans le même sens 
 		     * que <path> vu ci-haut
 		     * 
 		     */
 		    gr.append("text")
 		        .style('fill', 'gray').text(function (d, i) {
 		            i = i > 8 ? (i + 1) : '0' + (i + 1);
 		            return i;
 		        })
 		        .attr('dx', function (d, i) {
 		            return (i * 15 + 109);
 		        })
 		        .transition().delay(500).duration(1000).ease("cubic")
 		        .attr('dx', function (d, i) {
 		            return (i * 22 + 109);
 		        })
 		        .attr('dy', 12)
 		        .style('font', '10px sans-serif');


 		    //Fin entête Calendrier




 		    for (var m = periode.From; m < periode.To; ++m) {


 		        dateCourante = new Date(periode.Year, m);
 		        var data = myCalendar(dateCourante);


 		        month = m < 9 ? '0' + (m + 1) : month = m + 1;
 		        var indiceConge = periode.Year + '-' + month;
                console.log(m);
                console.log(indiceConge);

 		        /*
 		         * Boucle sur les ressources dans le rendu json récupéré avec AJAX
 		         */

 		        jQuery.each(dataset.ressources, function (index, val) {

 		                width = opt.dimensions.svg.w; //Largeur calendrier
 		                height = opt.dimensions.svg.h; //Hauteur calendrier
 		                var svg = d3.select('#wrapper')
 		                    .append('svg')
 		                    .attr('width', width)
 		                    .attr('height', height);


 		                var group = svg.append('g');

 		                var iCs; // indice centre de service
 		                if (dataset['ressources'][index]['cs'] == '0') iCs = 'France';
 		                else iCs = 'CSM';




 		                group.append('path')
 		                    .style("fill", opt.color.Entete)
 		                    .attr('stroke', opt.color.stroke)
 		                    .attr('stroke-width', '1px')
 		                //rectangle pour contenir mois /ressources chevauchés en hauteur
 		                .attr('d', function (d, i) {
 		                        return dPath(0, -20, opt.dimensions.month.w, opt.dimensions.month.h, opt.dimensions.month.round, opt.dimensions.month.round, opt.dimensions.month.round, opt.dimensions.month.round);
 		                    })
 		                    .transition()
 		                    .delay(250)
 		                    .duration(1500)
 		                    .ease("elastic")
 		                //rectangle pour contenir mois/mois ressources aprés transition
 		                .attr('d', function (d, i) {
 		                        return dPath(0, 0, opt.dimensions.month.w, opt.dimensions.month.h, opt.dimensions.month.round, 0, 0, opt.dimensions.month.round);
 		                    });


 		                group.append('text')
 		                    .attr('x', 4)
 		                    .attr('y', -22)
 		                    .attr('heigth', opt.dimensions.month.h)
 		                    .text(dataset['ressources'][index]['Nom'])
 		                    .transition()
 		                    .delay(250)
 		                    .duration(1500)
 		                    .ease("elastic")
 		                    .attr('y', 5)
 		                    .attr('dy', '1.7ex')
 		                    .attr('dx', '0.2ex').style('fill', 'gray')
 		                    .style('font', '10px sans-serif')
 		                    .attr('text-anchor', 'right');

 		                var group2 = svg.append('g');
 		                var nodegroups = group2.selectAll('path')
 		                    .data(data).enter().append('g').attr('rel', 'tooltip')
 		                    .attr('title', function (d, i) {
 		                        day = i < 9 ? '0' + (i + 1) : day = i + 1;
 		                        month = m < 9 ? '0' + (m + 1) : (m + 1);

 		                        var tdate = periode.Year + "-" + month + "-" + day;

 		                        if (dataset['Ferie'][iCs][tdate]) {
 		                            return dataset['Ferie'][iCs][tdate] + " : </br>" + d + ' ' + (i + 1) + ' ' + mois[data['mm']] + ' ' + periode.Year;
 		                        } else if ((i + 1) == tdd && data['mm'] == tmm) {
 		                            return 'Aujourd\'hui : ' + d + ' ' + (i + 1) + ' ' + mois[data['mm']] + ' ' + periode.Year;
 		                        }
 		                        return d + ' ' + (i + 1) + ' ' + mois[data['mm']] + ' ' + periode.Year;
 		                    });


 		                nodegroups.append('path').attr('fill', '#ededed')
 		                    .attr('d', function (d, i) {
 		                        var dPathx = (i * 13);
 		                        return dPath(Math.random(i) + opt.dimensions.month.w + opt.dimensions.month.margin, 0, 540, 30, 5, 0, 5, 0);
 		                    })

 		                .transition().delay(1500).duration(2000).ease("cubic")

 		                .attr('d', function (d, i) {

 		                        var dPathx = (i * (opt.dimensions.day.w + opt.dimensions.day.margin));

 		                        day = i > 8 ? (i + 1) : '0' + (i + 1);
 		                        month = m < 9 ? '0' + (m + 1) : (m + 1);
 		                        tdate = periode.Year + "-" + month + "-" + day;

 		                      if(dataset['ressources'][index]['conge'][indiceConge])
 		                    	  {
 		                       
	 		                        	var tdatedm = dataset['ressources'][index]['conge'][indiceConge]['Date'];
	
	 	 		                       
	 	 		                        var tdatefm = dataset['ressources'][index]['conge'][indiceConge]['Date'];
	
	 	 		                        if (tdate == tdatedm) {
	
	 	 		                            if (typeof  dataset['ressources'][index]['conge'][indiceConge] != 'undefined' && isExist(dataset['ressources'][index]['conge'][indiceConge],'DebutMidi','true')) {
	 	 		                                var dPathxdmf = (i * 10);
	 	 		                                var dPathxdm = (i * (20 + opt.dimensions.day.margin)) + 10 + opt.dimensions.day.offset;
	
	 	 		                                return dPath(dPathxdm, 0, 10, 20, 5, 0, 0, 5);
	 	 		                            }
	 	 		                        } else if (tdate == tdatefm) {
	 	 		                           
	 	 		                            if (dataset['ressources'][index]['conge'][indiceConge]['FinMidi'] == true) {
	
	 	 		                                var dPathxdm = (i * (20 + opt.dimensions.day.margin)) + opt.dimensions.day.offset;
	
	 	 		                                return dPath(dPathxdm, 0, 10, 20, 0, 5, 5, 0);
	 	 		                            }

	 	 		                        						}
 		                    	  }	
 		                       

 		                        if (d == 'Samedi') {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 5, 0, 0, 5);
 		                        } else if (d == 'Dimanche') {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 0, 5, 5, 0);
 		                        } else if (dataset['Ferie'][iCs][tdate]) {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 11, 11, 11, 11);
 		                        } else if (typeof  dataset['ressources'][index]['conge'][indiceConge] != 'undefined' && isExist(dataset['ressources'][index]['conge'][indiceConge],'Date',tdate)) {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 5, 5, 5, 5);
 		                        } else if ((i + 1) == tdd && data['mm'] == tmm) {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 5, 0, 5, 0);
 		                        } else {
 		                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 0, 0, 0, 0);
 		                        }




 		                    })

 		                .attr('fill', function (d, i) {

 		                        day = i > 8 ? (i + 1) : '0' + (i + 1);
 		                        month = m > 9 ? (m + 1) : '0' + (m + 1);

 		                        tdate = periode.Year + "-" + month + "-" + day;
 		                        today = new Date();
 		                        year = today.getFullYear();

 		                        //  	congeCount = Object.keys(dataset['ressources'][index]['conge']).length;


 		                       if (typeof  dataset['ressources'][index]['conge'][indiceConge] != 'undefined' && isExist(dataset['ressources'][index]['conge'][indiceConge],'Date',tdate)) {
		                          return opt.color.CP;
 		                        } else if (dataset['Ferie'][iCs][tdate]) {
 		                            return opt.color.FE;
 		                        } else if (d == 'Samedi') {
 		                            return opt.color.WE;
 		                        } else if (d == 'Dimanche') {
 		                            return opt.color.WE;
 		                        } else if ((i + 1) == tdd && data['mm'] == tmm && periode.Year == year) {
 		                            return '#E6AE00';
 		                        } else {
 		                            return '#ededed';
 		                        }

 		                    })
 		                    .attr('stroke', function (d, i) {
 		                        day = i > 8 ? (i + 1) : '0' + (i + 1);
 		                        month = m > 9 ? (m + 1) : '0' + (m + 1);
 		                        year = today.getFullYear();
 		                        tdate = periode.Year + "-" + month + "-" + day;
 		                        if ((i + 1) == tdd && data['mm'] == tmm && data['yyyy'] == year) {
 		                            return '#E6AE00';
 		                        }




 		                    });



 		                var nodetext = nodegroups.append("text")
 		                    .attr('x', function (d, i) {
 		                        return i * 22 + opt.dimensions.day.offset + 3;
 		                    })
 		                    .attr('y', 13)
 		                    .transition()
 		                    .delay(1500)
 		                    .style('font', '9px sans-serif')
 		                    .text(function (d, i) {

 		                        day = i > 8 ? (i + 1) : '0' + (i + 1);
 		                        month = m > 9 ? (m + 1) : '0' + (m + 1);

 		                        tdate = periode.Year + "-" + month + "-" + day;


 		                        if (typeof  dataset['ressources'][index]['conge'][indiceConge] != 'undefined' && isExist(dataset['ressources'][index]['conge'][indiceConge],'Date',tdate)) {
 		                            return 'CP';
 		                        } else if (dataset['Ferie'][iCs][tdate]) {
 		                            return 'FE';
 		                        } else if (d == 'Samedi') {
 		                            return 'SA';
 		                        } else if (d == 'Dimanche') {
 		                            return 'DI';
 		                        } else if ((i + 1) == tdd && data['mm'] == tmm) {
 		                            return 'AU';
 		                        } else {
 		                            return '';
 		                        }

 		                    })
 		                    .attr('fill', '#FFFFFF');




 		            });

 		    }


 		    return;


 		}

 		$(document).ready(function () {
 		
 		        $('#chargerCalendrier').unbind('click').bind('click',function () {
 		        	
 		        		d3.selectAll('svg').remove();
 		             
 		                var id_personne = $('#personne').val();
 		                var mois = $('#mois').val();
 		                var annee = $('#annee').val();

 		                $.ajax({
 		                        type: 'POST',
 		                        url: './calendrier-mensuel-beta',
 		                        dataType: 'json',
 		                        data: {
 		                            annee: annee,
 		                            mois: mois,
 		                            id_personne: id_personne
 		                      
 		                        },
 		                        success: function (data, status) {

 		                        	
 		                        	
 		                            width = 900;
 		                            height = 22;

 		                            svgContainer = d3.select('#wrapper').append('svg').attr('width', width).attr('height', height);

 		                            periode = {
 		                                "Year": parseInt(annee),
 		                                "From": parseInt(mois),
 		                                "To": parseInt(mois)
 		                            };
 		                            DrawMonthCalendar(periode, calendarOptions, data);


 		                        },
 		                        error: function () {}


 		                    });


 		            });




 		    });