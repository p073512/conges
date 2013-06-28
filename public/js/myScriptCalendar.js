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


/**
 * Tableau en format JSON pour contenir les options du calendrier.
 *
 * Les options du calendrier touche surtout son aspect visuel :
 * palette couleur , taille , espacement..
 *
 *
 */
var calendarOptions = {
    "color": { // Couleur Case selon code congé ou type jour 
        "AU": "#E6AE00",
        "N": "#EDEDED",
        "WE": "#D1D1D1", //"#327CCB",
        "FE": "#B2655D", //"#A74143",
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
            "h": 20

        },

        "day": { //dimensions de la case jour
            "w": 20,
            "h": 20,
            "round": 8,
            "margin": 2,
            "offset": 155
        },

        "month": { //dimensions de la case mois / ressources
            "w": 150,
            "h": 20,
            "round": 8,
            "margin": 4

        }
    }
};


/***
 * @desc myCalendar fonction qui calcule le nombre jour , puis le nom du jour du mois
 *  de la date passée en argument 
 * 
 *
 *
 * @param date
 * @returns {___days0} : tableau associatif d[i] = nomjour: d[mm] : mois de @date d[yyy] année de @date
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



    if (new Date(yyyy, 1, 29)
        .getMonth() == 1) // si  année bissextile (vérifie si le 29/02 de l'année courante existe)
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
    days['mm'] = mm; // mois de la date passée en argument
    days['yyyy'] = yyyy; // année de la date passée en argument
    return days;

}


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

                if (date == structure[i]['0']['Date'] && date == structure[i][daysCount - 1]['Date']) {


                    if (structure[i]['0']['DebutMidi'] == 'false' && structure[i]['0']['FinMidi'] == 'false') {
                        // console.log(date +' => '+i+ ' dd == df | debutMidi : False, FinMidi false' );
                        data[date] = {};
                        data[date] = structure[i][date]['TypeConge'];

                    } else if (structure[i]['0']['DebutMidi'] == true) {

                        //  console.log(date +' => '+i+ 'dd == df | debutMidi : true, FinMidi false' );

                        if (typeof data[date] == "undefined") {
                            data[date] = {};
                            data[date].dm = structure[i][date]['TypeConge'];
                        } else if (data[date].fm == structure[i][date]['TypeConge'])
                            data[date].j = structure[i][date]['TypeConge'];
                        else if (data[date].fm !== structure[i][date]['TypeConge'])
                            data[date].dm = structure[i][date]['TypeConge'];


                    } else if (structure[i]['0']['FinMidi'] == true) {
                        //  console.log(date +' => '+i+ 'dd == df | debutMidi : false, FinMidi true' );

                        if (typeof data[date] == "undefined") {
                            data[date] = {};
                            data[date].fm = structure[i][date]['TypeConge'];
                        } else if (data[date].dm == structure[i][date]['TypeConge'])
                            data[date].j = structure[i][date]['TypeConge'];
                        else if (data[date].dm !== structure[i][date]['TypeConge'])
                            data[date].fm = structure[i][date]['TypeConge'];


                    }

                } else if ((date == structure[i]['0']['Date'] && date !== structure[i][daysCount - 1]['Date']) || (date !== structure[i]['0']['Date'] && date == structure[i][daysCount - 1]['Date'])) {


                    if (structure[i]['0']['DebutMidi'] == true && date == structure[i]['0']['Date']) {
                        //	console.log(date +' => '+i+ 'dd !== df | debutMidi : true' );

                        if (typeof data[date] == "undefined") {
                            data[date] = {};
                            data[date].dm = structure[i][date]['TypeConge'];
                        } else if (data[date].fm == structure[i][date]['TypeConge'])
                            data[date].j = structure[i][date]['TypeConge'];
                        else if (data[date].fm !== structure[i][date]['TypeConge'])
                            data[date].dm = structure[i][date]['TypeConge'];


                    } else if (structure[i]['0']['DebutMidi'] == false) {
                        //		console.log(date +' => '+i+ 'dd !== df | debutMidi : False' );
                        data[date] = {};
                        data[date].j = structure[i][date]['TypeConge'];

                    }
                    if (structure[i][daysCount - 1]['FinMidi'] == true && date == structure[i][daysCount - 1]['Date']) {
                        // console.log(date +' => '+i+ 'dd !== df | FinMidi : true' );

                        if (typeof data[date] == "undefined") {
                            data[date] = {};
                            data[date].fm = structure[i][date]['TypeConge'];
                        } else if (data[date].dm == structure[i][date]['TypeConge'])
                            data[date].j = structure[i][date]['TypeConge'];
                        else if (data[date].dm !== structure[i][date]['TypeConge'])
                            data[date].fm = structure[i][date]['TypeConge'];


                    } else if (structure[i][daysCount - 1]['FinMidi'] == false) {
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
                    return data;
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


function DrawMonthCalendar(periode, opt, dataset, filtre) {


    /*
     * On Supprime toutes les balises SVG
     *
     */
    jQuery('.hero-unit')
        .remove();

    d3.selectAll('svg')
        .style("opacity", 1)
        .transition()
        .duration(400)
        .style("opacity", 0)
        .remove();



    // balise svg incluse dans le div conteneur dont l'id est 'wrapper'
    svgContainer = d3.select('#wrapper')
        .append('svg')
        .attr('width', opt.dimensions.svg.w)
        .attr('height', opt.dimensions.svg.h);



 
    if (periode.To == periode.From) // Calendrier d'un seul mois (possibilité d'affiché plusieurs mois)
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

    data.unshift(mois[periode.From] + ' ' + periode.Year); // premier rectangle de l'entete calendrier : 'mois annee'
    data.push('Nombre Jours'); // dernier rectancle de l'entete calendrier : 'nombre jours'

    date = new Date(); // pour vérifier si la date en arguement est le jour d'aujourd'hui
    datejour = date.getDate();
    moisCourant = date.getMonth();
    anneeCourante = date.getFullYear();
    
    gr = svgContainer.selectAll('g')
        .data(data) // data contenant les informations pour l'affichage du calendrier (voir myCalendar())
    .enter()
        .append('g'); // Ajout de balise <g> (pour regroupement) pour chaque item du data[]


    gr.append('path') // dans chaque tag <g> (précédemment ajouté) on ajout une balise path(chemin) sous forme de cercle

    .attr('fill', function (d, i) {

        if (datejour == i && data[0] == mois[moisCourant] + ' ' + anneeCourante) {
            return opt.color.AU;
        }
        if (i == data.length - 1) {
            return '#327CCB';
        }
        return '#EDEDED';
    }) // couleur de fond
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
         * l'attribut "d" est resseté pour prendre les coordonnées
         * de l'emplacement correcte des ronds jours de l'entête
         *
         */
        var dPathx = (i * 22);

        if (i == 0) // premiere case (mois annee)
        {
            return dPath(0, 0, opt.dimensions.month.w, opt.dimensions.month.h, opt.dimensions.month.round, 0, 0, opt.dimensions.month.round);
        }


        if (i == (data.length - 1)) { // si case nombre jour atteinte 
            return dPath(dPathx + 133, 0, 70, opt.dimensions.day.h, 0, 5, 5, 0);
        }


        // cases médianes contenant les jours du mois (rondes)
        return dPath(dPathx + 133, 0, 20, 20, 11, 11, 11, 11);
    });


    /*
     * le text affiché sur les cercles notamment le numéro du jour
     * l'alimentation des propriété de <text> se fait de la même sorte
     * que <path> vu ci-haut
     *
     */
    gr.append("text")
        .style('fill', function (d, i) {
            if (i == data.length - 1) {
                return '#EDEDED';
            }
            return 'gray';
        })
        .text(function (d, i) {
            if (i == 0) {
                return data[0];
            }
            if (i == data.length - 1) {

                return data[data.length - 1];
            }

            i = i - 1;
            i = i > 8 ? (i + 1) : '0' + (i + 1);
            return i;
        })
        .attr('dx', function (d, i) {


            return (i * 15 + 137);
        })
        .transition()
        .delay(500)
        .duration(1000)
        .ease("cubic")
        .attr('dx', function (d, i) {
            if (i == 0) {
                return 50;
            }


            return (i * 22 + 137);
        })
        .attr('dy', 12)
        .style('font', '10px sans-serif');


    //Fin entête Calendrier


    for (var m = periode.From; m < periode.To; ++m) {

        
        dateCourante = new Date(periode.Year, m);
        /*
         * On passe la date formée à partir du mois et année renseignés sur le formulaire
         * pour récupérer le tableau associatif contenant le détail des jours du mois via myCalendar.
         */
        
        var data = myCalendar(dateCourante); 

        //mois formaté sur deux digit pour faire correspondre avec l'indice conge reçu du serveur via ajax.
        month = m < 9 ? '0' + (m + 1) : month = m + 1;
        var indiceConge = periode.Year + '-' + month;

        /*
         * Boucle sur les ressources dans le rendu json récupéré avec AJAX
         * pour chaque ressources on dessine une ligne qui correspond au mois choisi
         */


        dataLength = data.length; 
        data[dataLength] = 0; // on rajoute une case pour contenir le nombre jours .
        var jcount = 0; //Contient le nombre de ressources affichés si 0 toutes les ressources travaillent .
       
        jQuery.each(dataset.ressources, function (index, val) { // Parcourir ressource par ressource


            count = 0; // contiendra la somme des nombres jours congés sur le mois / ressources
            if (typeof dataset['ressources'][index]['conge'][indiceConge] !== 'undefined') {
                jcount++;
                jQuery.each(dataset['ressources'][index]['conge'][indiceConge], function (iConge, val) {

                    count += parseFloat(dataset['ressources'][index]['conge'][indiceConge][iConge]['nombreJours']);
                });

                console.log('count => ' + jcount);
                data[dataLength] = count;

                var iCs; // indice centre de service pour puiser dans la structure dans les jours fériés selon l'indice CS.
                if (dataset['ressources'][index]['cs'] == '0')
                    iCs = 'France';
                else
                    iCs = 'CSM';

                dataJson = [];
                j = 0;
                jQuery.each(data, function (i, val) {
                	
                	
                    if (i !== dataLength) {
                        day = i < 9 ? '0' + (i + 1) : day = i + 1;
                        month = m < 9 ? '0' + (m + 1) : (m + 1);
                        var thisDate = periode.Year + "-" + month + "-" + day;



                        title = data[i] + ' ' + (i + 1) + ' ' + mois[data['mm']] + ' ' + periode.Year;
                        typeConge = null;


                        thisDate = periode.Year + "-" + month + "-" + day;
                        //console.log(data[i] + '=>' + i);

                        if (data[i] == 'Samedi') {
                            dataJson[j] = {
                                "date": thisDate,
                                "typeJour": 'SA',
                                "typeConge": typeConge,
                                "title": title,
                                "jour": i
                            };
                            j++;
                            return true;
                        } else if (data[i] == 'Dimanche') {
                            dataJson[j] = {
                                "date": thisDate,
                                "typeJour": 'DI',
                                "typeConge": typeConge,
                                "title": title,
                                "jour": i
                            };
                            j++;
                            return true;
                        }

                        if (dataset['Ferie'][iCs][thisDate]) {
                            title = dataset['Ferie'][iCs][thisDate] + " : </br>" + title;

                            dataJson[j] = {
                                "date": thisDate,
                                "typeJour": 'FE',
                                "typeConge": typeConge,
                                "title": title,
                                "jour": i
                            };
                            j++;
                            return true;
                        }



                        if (typeof dataset['ressources'][index]['conge'][indiceConge] !== "undefined") {
                            tConge = isExist(dataset['ressources'][index]['conge'][indiceConge], thisDate);

                        }
                        if (typeof tConge !== 'undefined') {
                            if (typeof tConge[thisDate] == 'undefined') { // si date est prise en entier comme congé  
                                dataJson[j] = {
                                    "date": thisDate,
                                    "typeJour": 'C',
                                    "typeConge": tConge,
                                    "title": title,
                                    "jour": i
                                };
                                j++;
                                return true;
                            } else if (tConge[thisDate].dm && tConge[thisDate].fm) { //si deux demi journée de congé sur même jour ( de congé différent)

                                dataJson[j] = {
                                    "date": thisDate,
                                    "typeJour": 'FM',
                                    "typeConge": tConge[thisDate].fm,
                                    "title": title,
                                    "jour": i
                                };
                                dataJson[j + 1] = {
                                    "date": thisDate,
                                    "typeJour": 'DM',
                                    "typeConge": tConge[thisDate].dm,
                                    "title": title,
                                    "jour": i
                                };
                                j = j + 2;
                                return true;
                            } else if (tConge[thisDate].dm && !tConge[thisDate].fm) { 
                                dataJson[j] = {
                                    "date": thisDate,
                                    "typeJour": 'FM',
                                    "typeConge": 'NaN',
                                    "title": title,
                                    "jour": i
                                };
                                dataJson[j + 1] = {
                                    "date": thisDate,
                                    "typeJour": 'DM',
                                    "typeConge": tConge[thisDate].dm,
                                    "title": title,
                                    "jour": i
                                };
                                j = j + 2;
                                return true;
                            } else if (tConge[thisDate].fm && !tConge[thisDate].dm) {
                                dataJson[j] = {
                                    "date": thisDate,
                                    "typeJour": 'FM',
                                    "typeConge": tConge[thisDate].fm,
                                    "title": title,
                                    "jour": i
                                };
                                dataJson[j + 1] = {
                                    "date": thisDate,
                                    "typeJour": 'DM',
                                    "typeConge": 'NaN',
                                    "title": title,
                                    "jour": i
                                };
                                j = j + 2;
                                return true;
                            }

                        } else {
                            dataJson[j] = {
                                "date": thisDate,
                                "typeJour": 'N',
                                "typeConge": typeConge,
                                "title": title,
                                "jour": i
                            };
                            j++;
                            return true;
                        }


                    } else {

                        dataJson[j] = {
                            "nombreJours": data[i],
                            "indice": i
                        };
                        j++;
                    }

                });

                console.log('dataJson');
                console.log(dataJson);

                width = opt.dimensions.svg.w; //Largeur calendrier
                height = opt.dimensions.svg.h; //Hauteur calendrier

                //#wrapper est l'id du div conteneur du calendrier.<svg>
                var svg = d3.select('#wrapper')
                    .append('svg')
                    .attr('width', width)
                    .attr('height', height);

                //#chaque ressource est encapsulé dans un tag <g> 
                var group = svg.append('g');


                /*On dessine le path (rectangle)contenant le nom de ressource avec les options
                 *  (fill : couleur)
                 *  (stroke : bordure)
                 *  (stroke-width : largeur de la bordure)
                 *
                 */
                group.append('path')
                    .style("fill", opt.color.Entete)
                    .attr('stroke', opt.color.stroke)
                    .attr('stroke-width', '1px')

                /*L'attribut 'd' indique la forme du rectangle pour contenir mois /ressources disposés verticalement
                 * ici forme du rectangle avant transition.
                 */
                .attr('d', function (d, i) {
                    return dPath(0, -20, opt.dimensions.month.w, opt.dimensions.month.h, opt.dimensions.month.round, opt.dimensions.month.round, opt.dimensions.month.round, opt.dimensions.month.round);
                })
                    .transition() // début transition
                .delay(250)
                    .duration(1500)
                    .ease("elastic") // délai pour déclenchement, durée, effet de la transition
                /*
                 * forme du rectangle pour contenir mois/mois ressources aprés transition
                 */
                .attr('d', function (d, i) {
                    return dPath(0, 0, opt.dimensions.month.w, opt.dimensions.month.h, opt.dimensions.month.round, 0, 0, opt.dimensions.month.round);
                });

                /*
                 * text dans le rectangle (nom de la ressources
                 *
                 */
                group.append('svg:title')
                    .text('Pole : ' + dataset['ressources'][index]['Pole']['libelle'] + '</br>Fonction : ' + dataset['ressources'][index]['Fonction']['libelle'] + '</br>Entite : ' + dataset['ressources'][index]['Entite']['libelle']);
                group.append('text')
                    .attr('x', 4)
                    .attr('y', -22)
                    .attr('heigth', opt.dimensions.month.h)

                .text(dataset['ressources'][index]['Nom']) // récupération du nom à partir de la structure passé en argument
                .transition()
                    .delay(250)
                    .duration(1500)
                    .ease("elastic")
                // aprés transition
                .attr('y', 5)
                    .attr('dy', '1.7ex')
                    .attr('dx', '0.2ex')
                    .style('fill', 'gray')
                    .style('font', '10px sans-serif')
                    .attr('text-anchor', 'right');

                // un deuxieme tag <g> pour englober les jours du mois
                var group2 = svg.append('g');
                var nodegroups = group2.selectAll('path')
                    .data(dataJson)
                    .enter() // ajoute la balise quand elle n'existe 
                .append('g');



                  
                nodegroups.append('svg:title')
                    .text(function (d, i) {

                        if (i == (data.length - 1)) {
                            return 'Nombre jours congés';
                        }

                        return d.title;

                    });

                nodegroups.append('path')
                    .attr('fill', '#ededed')
                    .attr('d', function (d, i) {
                        var dPathx = (i * 13);
                        return dPath(Math.random(i) + opt.dimensions.month.w + opt.dimensions.month.margin, 0, 540, 30, 5, 0, 5, 0);
                    })

                .transition()
                    .delay(1500)
                    .duration(2000)
                    .ease("cubic")
                // forme cases jours selon type jours
                .attr('d', function (d, i) {
                    // form 'd' pour jour normal
                    var dPathx = (d.jour * (opt.dimensions.day.w + opt.dimensions.day.margin));

                    if (i == (dataJson.length - 1)) {
                        dPathx = (d.indice * (opt.dimensions.day.w + opt.dimensions.day.margin));
                        return dPath(dPathx + opt.dimensions.day.offset, 0, 70, opt.dimensions.day.h, 0, 5, 5, 0);
                    }


                    if (d.typeConge == null) {

                        if (d.typeJour == 'SA') {
                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 5, 0, 0, 5);
                        } else if (d.typeJour == 'DI') {
                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 0, 5, 5, 0);
                        } else if (d.typeJour == 'FE') {
                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 11, 11, 11, 11);
                        } else if (d.typeJour == 'N') {
                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 0, 0, 0, 0);
                        }

                    } else {
                        if (d.typeJour == 'FM' && d.typeConge == 'NaN') {
                            var dPathxdm = (d.jour * (20 + opt.dimensions.day.margin)) + opt.dimensions.day.offset;
                            return dPath(dPathxdm, 0, 9.5, 20, 0, 0, 0, 0);
                        } else if (d.typeJour == 'FM') {
                            var dPathxdm = (d.jour * (20 + opt.dimensions.day.margin)) + opt.dimensions.day.offset;
                            return dPath(dPathxdm, 0, 9.5, 20, 0, 5, 5, 0);

                        }
                        if (d.typeJour == 'DM' && d.typeConge == 'NaN') {
                            var dPathxdm = (d.jour * (20 + opt.dimensions.day.margin)) + 10 + opt.dimensions.day.offset;
                            return dPath(dPathxdm, 0, 9.5, 20, 0, 0, 0, 0);
                        } else if (d.typeJour == 'DM') {
                            var dPathxdm = (d.jour * (20 + opt.dimensions.day.margin)) + 10 + opt.dimensions.day.offset;
                            return dPath(dPathxdm, 0, 9.5, 20, 5, 0, 0, 5);
                        }
                        if (d.typeJour == 'C') {
                            return dPath(dPathx + opt.dimensions.day.offset, 0, opt.dimensions.day.w, opt.dimensions.day.h, 5, 5, 5, 5);
                        }

                    }



                })
                // couleur cases jours selon type jours
                .attr('fill', function (d, i) {
                    if (i == (dataJson.length - 1)) {
                        return '#327CCB';
                    }

                    if (d.typeConge == null) {

                        if (d.typeJour == 'SA' || d.typeJour == 'DI') {
                            return opt.color.WE;
                        } else if (d.typeJour == 'FE') {
                            return opt.color.FE;
                        } else if (d.typeJour == 'N') {
                            return opt.color.N;
                        }

                    } else {
                        if (d.typeConge == 'NaN')
                            return opt.color.N;
                        else
                            return opt.color[d.typeConge];

                    }


                });



                var nodetext = nodegroups.append("text")
                    .attr('x', function (d, i) {
                        if (i == (dataJson.length - 1)) {
                            return d.indice * 22 + opt.dimensions.day.offset + 3;
                        }
                        if (d.typeConge != null) {
                            if (d.typeJour == 'DM') {
                                return d.jour * 22 + 10 + opt.dimensions.day.offset + 3;
                            }
                        }
                        return d.jour * 22 + opt.dimensions.day.offset + 3;
                    })
                    .attr('y', 13)
                    .transition()
                    .delay(1500)
                    .style('font', function (d, i) {

                        if ((d.typeJour == 'DM' || d.typeJour == 'FM') && d.typeConge.length > 1) {
                            return '8px sans-serif';
                        }
                        return '9px sans-serif';
                    })

                .text(function (d, i) {
                    if (i == (dataJson.length - 1)) {
                        return d.nombreJours + ' Jour(s)';
                    }

                    if (d.typeConge == null) {
                        if (d.typeJour == 'N') {
                            return '';
                        } else {
                            return d.typeJour;
                        }


                    } else {
                        if (d.typeConge == 'NaN')
                            return '';
                        else
                            return d.typeConge;

                    }

                })
                    .attr('transform', function (d, i) {


                        if (d.typeConge !== null) {
                            if ((d.typeJour == 'DM' || d.typeJour == 'FM') && d.typeConge != 'NaN')
                                if (d.typeConge.length > 1) {
                                    x = parseInt(d3.select(this)
                                        .attr("x")) + 3;
                                    var rotation = "rotate(90," + x + ",9)";
                                    return rotation;
                                }
                        }




                        return; // warning rotate transform doesn't accept null value !
                    })

                .attr('fill', function (d, i) {
                    if (i == (dataJson.length - 1)) {
                        return '#FFFFFF';
                    } else
                    if (d.typeConge !== null) {
                        return '#4B000F';
                    }
                    return '#FFFFFF';
                });

            }


        });

        /*
         * msg en cas ou tout le monde bosse sur le mois affiché
         * 
         * 
         * 
         */
        
        if (typeof filtre !== 'undefined') {
            if (jcount == 0)
                 // msg en cas de 
                jQuery('#wrapper')
                    .append('<div class="hero-unit" ><h1>Pas de congés</h1><p>Aucun résultat ne correspond aux filtres</p></div>');

        } else {
            $('.selectpicker')
                .selectpicker('deselectAll');
            if (jcount == 0)
                jQuery('#wrapper')
                    .append('<div class="hero-unit" ><h1>Pas de congés</h1><p>Tous le monde bosse ce mois</p></div>');
        }


    }

    jQuery('svg g title')
        .parent()
        .tipsy({
            gravity: 'sw',
            hoverlock: true,
            html: true,
            title: function () {
                return $(this)
                    .find('title')
                    .text();
            }
        });

    return dataset;


}

/*
 * fonction qui charge le calendrier . via une requête ajax. 
 * 
 * 
 */
function getCalendarContent(idPersonne, mois, annee) {


    $('#myModal')
        .modal('show');

    // envoie du requête ajax à l'action avec les paramétre du formulaire

    jQuery.ajax({
        type: 'POST',
        async: false,
        url: './calendrier-mensuel-beta',
        dataType: 'json', // échange en format JSON
        data: {
            annee: annee,
            mois: mois,
            id_personne: idPersonne

        },
        success: function (data, status) {

            // Période qui sera affiché dans le calendrier.
            periode = {
                "Year": parseInt(annee),
                "From": parseInt(mois),
                "To": parseInt(mois)
            };

            // on dessine le calendrier
            DrawMonthCalendar(periode, calendarOptions, data);

            // setTimeout(function(data){calendarData = null;alert('Finito'+ calendarData);},60000);
            $('#myModal')
                .modal('hide');


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
/*
 * Fonction qui se charge de l'alimentation du menu de navigation entre mois en bas de page.
 * 
 * 
 */

function setNavMonthMenu(action, month, year) {
    // Menu nvigation entre mois du calendrier 
    date = new Date();
    var i;


    if (typeof action == 'undefined') {
        iMoisCourant = date.getMonth();
        anneeCourante = date.getFullYear();

    } else if (action == 'first') {
        iMoisCourant = 0;
        anneeCourante = date.getFullYear() - 1;
    } else if (action == 'last') {
        iMoisCourant = 11;
        anneeCourante = date.getFullYear() + 1;
    } else if (action == 'set') {

        if (typeof month !== 'undefined' && typeof year !== 'undefined') {
            iMoisCourant = jQuery('#mois')
                .val();
            iMoisCourant = parseInt(iMoisCourant);

            anneeCourante = jQuery('#annee')
                .val();
            anneeCourante = parseInt(anneeCourante);
        } else {
            //console.log('Error Function Call undefined param : setNavMonthMenu()');
        }



    } else {


        iMoisCourant = jQuery('button[id="' + action + '"]')
            .attr('mois');
        iMoisCourant = parseInt(iMoisCourant);

        anneeCourante = jQuery('button[id="' + action + '"]')
            .attr('annee');
        anneeCourante = parseInt(anneeCourante);

        if (action == 'next') {
            //console.log(iMoisCourant + '=>' + anneeCourante);

            i = 1;

            if (iMoisCourant == 11 && anneeCourante == date.getFullYear() + 1) {
                jQuery('#next')
                    .html('NC');
            } else if (iMoisCourant == 11) {
                anneeCourante += i;
                iMoisCourant = 0;
            } else {
                iMoisCourant += i;
            }

        } else if (action == 'prev') {
            i = -1;

            if (iMoisCourant == 0 && anneeCourante == date.getFullYear() - 1) {
                jQuery('#prev')
                    .html('NC');
            } else if (iMoisCourant == 0) {
                anneeCourante += i;
                iMoisCourant = 11;

            } else {
                iMoisCourant += i;
            }

        }



    }

    var iNextMois, iPrevMois;
    if (iMoisCourant == 11)
        iNextMois = 0;
    else
        iNextMois = iMoisCourant + 1;

    if (iMoisCourant == 0)
        iPrevMois = 11;
    else
        iPrevMois = iMoisCourant - 1;

    jQuery('select#mois option[value=' + iMoisCourant + ']')
        .attr('selected', true);
    jQuery('select#annee option[value=' + anneeCourante + ']')
        .attr('selected', true);

    jQuery('button[id=moisAnnee]')
        .html('<span class="win-commandimage">&#xe138;</span><span id="moisAnnee" class="win-label ">' + mois[iMoisCourant] + ' ' + anneeCourante + '</span>');

    jQuery('button[id=next]')
        .attr('mois', iMoisCourant)
        .attr('annee', anneeCourante);
    jQuery('button[id=next]')
        .html('<span class="win-commandimage win-commandring">&#xe1ca;</span><span id="next" class="win-label">' + mois[iNextMois] + '</span>');

    jQuery('button[id=prev ]')
        .attr('mois', iMoisCourant)
        .attr('annee', anneeCourante);
    jQuery('button[id=prev]')
        .html('<span class="win-commandimage win-commandring">&#xe1cd;</span><span id="prev" class="win-label">' + mois[iPrevMois] + '</span>');




    return {
        "iMois": iMoisCourant,
        "annee": anneeCourante
    };
}

jQuery(document)
    .ready(function () {
        var calendarData = null;
        var pole, fonction, entite = [];
        var currentDate;



        jQuery.noConflict();



        /*
         * instanciation du menu calendrier et chagrement du calendrier
         * du mois courant.
         */
        currentDate = setNavMonthMenu();
        calendarData = getCalendarContent('x', currentDate.iMois, currentDate.annee);

        console.log('calendarData');
        console.log(calendarData);


        //Constructeur des liste déroulante select mutliple
        $('.selectpicker')
            .selectpicker();


        //Clic sur l'icone période . affichage du menu de paramétrage à droite
        jQuery("#settings")
            .live('click', function (e) {

                e.preventDefault();
                jQuery('.settings')
                    .show();
                // clic sur l'icone "->" pour cacher le menu de paramétrage.
                jQuery(".close-charms")
                    .live('click', function (e) {
                        jQuery('.settings')
                            .hide();
                    });
            });




        //Clic sur l'icone Fitlre . affichage du menu de paramétrage à droite
        jQuery("#filtres")
            .live('click', function (e) {
                e.preventDefault();
                jQuery('.filtre')
                    .show();
                // clic sur l'icone "->" pour cacher le menu de paramétrage.
                jQuery(".filtre .close-charms")
                    .live('click', function (e) {
                        jQuery('.filtre')
                            .hide();
                    });
            });




        jQuery('button[id="recharger"]')
            .live('click', function () {


                currentDate = setNavMonthMenu();
                calendarData = getCalendarContent('x', currentDate.iMois, currentDate.annee);

            });

        jQuery('button[id="next"]')
            .live('click', function () {

                currentDate = setNavMonthMenu('next');
                var idPersonne = jQuery('#personne')
                    .val();
                calendarData = getCalendarContent(idPersonne, currentDate.iMois, currentDate.annee);

            });


        jQuery('button[id="prev"]')
            .live('click', function () {

                currentDate = setNavMonthMenu('prev');
                var idPersonne = jQuery('#personne')
                    .val();
                calendarData = getCalendarContent(idPersonne, currentDate.iMois, currentDate.annee);

            });

           
        jQuery('button[id="first"]')
            .live('click', function () {

                currentDate = setNavMonthMenu('first');
                var idPersonne = jQuery('#personne')
                    .val();
                calendarData = getCalendarContent(idPersonne, currentDate.iMois, currentDate.annee);
            });

        jQuery('button[id="last"]')
            .live('click', function () {

                currentDate = setNavMonthMenu('last');
                var idPersonne = jQuery('#personne')
                    .val();
                calendarData = getCalendarContent(idPersonne, currentDate.iMois, currentDate.annee);
            });



           
        jQuery('#chargerCalendrier')
            .unbind('click')
            .bind('click', function () {

                // récupération des valeurs entrées dans le formulaire
                var idPersonne = jQuery('#personne')
                    .val();
                var mois = jQuery('#mois')
                    .val();
                var annee = jQuery('#annee')
                    .val();

                currentDate = setNavMonthMenu('set', mois, annee);
                calendarData = getCalendarContent(idPersonne, mois, annee);



            });

        jQuery('#Filtrer') // au click sur le boutton filtrer
            .live('click', function () {
            	// tableaux qui contiendront les filtres selectionnés.
                entite = []; 
                pole = [];
                fonction = [];
                
                // recuperation des filtres selectionnée
                jQuery('#entite :selected')
                    .each(function (i, element) {
                        entite[i] = element.value;
                    });
                jQuery('#pole :selected')
                    .each(function (i, element) {
                        pole[i] = element.value;
                    });
                jQuery('#fonction :selected')
                    .each(function (i, element) {
                        fonction[i] = element.value;
                    });

                var filtredRessources = {}; 
                if (calendarData == null) { // si les données du calendrier n'existent pas càd le calendrier n'a jamais été chargé avant.
                    alert('charger le Calendrier d\'abord');
                } else {
                      // on filtre les ressources selon les criteres dans les tableau.
                    filtredRessources = jQuery.grep(calendarData.ressources, function (d, i) {
                        var resultPole;
                        var resultFonction;
                        var resultEntite;

                            
                        if (pole.length > 0) {
                            for (var i = 0; i < pole.length; i++) {
                                if (pole[i] !== 'undefined')
                                    if (d.Pole.value == pole[i]) {
                                        resultPole = true;
                                        break;
                                    }
                            }
                        } else {
                            resultPole = true;
                        }

                        if (fonction.length > 0) {
                            for (var i = 0; i < fonction.length; i++) {

                                if (d.Fonction.value == fonction[i]) {
                                    resultFonction = true;
                                    break;
                                }

                            }
                        } else {
                            resultFonction = true;
                        }

                        if (entite.length > 0) {
                            for (var i = 0; i < entite.length; i++) {

                                if (d.Entite.value == entite[i]) {
                                    resultEntite = true;
                                    break;
                                }
                            }

                        } else {
                            resultEntite = true;
                        }



                        and = jQuery('.filtre input[id=and]')
                            .attr('checked');
                        or = jQuery('.filtre input[id=or]')
                            .attr('checked');

                        if (and == true) {
                            /*
    				  console.log(d.Entite.value+' Entite =>' + resultEntite);
            		  console.log(d.Fonction.value+' Fonction =>'+ resultFonction);
            		  console.log(d.Pole.value+' Pole =>'+ resultPole);
    			        */
                            if (resultEntite == true && resultPole == true && resultFonction == true)
                                return true;
                            else
                                return false;
                        }

                        if (or == true) {
                            /*
    				  console.log(d.Entite.value+' Entite =>' + resultEntite);
            		  console.log(d.Fonction.value+' Fonction =>'+ resultFonction);
            		  console.log(d.Pole.value+' Pole =>'+ resultPole);
    				   */
                            if (resultEntite == true || resultPole == true || resultFonction == true)
                                return true;
                            else
                                return false;
                        }


                    });

                    //console.log(filtredRessources);
                    filtredData = {
                        "ressources": filtredRessources,
                        "Ferie": calendarData.Ferie
                    };
                    //console.log(filtredData);


                    // taille balise svg
                    width = 920;
                    height = 22;
                    // balise svg incluse dans le div conteneur dont l'id est 'wrapper'
                    svgContainer = d3.select('#wrapper')
                        .append('svg')
                        .attr('width', width)
                        .attr('height', height);

                    periode = {
                        "Year": parseInt(currentDate.annee),
                        "From": parseInt(currentDate.iMois),
                        "To": parseInt(currentDate.iMois)
                    };

                    DrawMonthCalendar(periode, calendarOptions, filtredData, 'filtre');

                }


            });


    });