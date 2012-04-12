// JavaScript Document
$(document).ready(function() {
	
	// Initialisation des variables
	var countQuestion = $('.number-off').length-1;

	var score = 0;
	var sizeWrapperNumber = ($('.number-off').length*70)+30;
	var idQuestion = 0;
	// definition taille wrapper-number
	$('#wrapper-number').css('width',sizeWrapperNumber+'px');
	// activation premiere puce
	$('#number'+(idQuestion+1)).attr('class','number');
	
	
	// demarrage quiz
		$('.panel.question1 .reponses ul li').css('margin-top','-380px');
		
		// changement du numero de question
		var numQuestion = $('.puce','#number1').text();
		$('.number-question').text(numQuestion);
		$('.question-current').fadeIn();
		$('.intitule-question').fadeIn();
		
		//deroulement du volet
		$('.link','#number1').slideDown('slow', function() {
    		$('.panel.question1').slideDown('slow', function(){
				$('.panel.question1 .reponses ul li').animate({'margin-top': '0px'},400);
			});
  		});

	
	//Clique flèche droite
	$('.arrow-right a').click(function(){
		
		if(idQuestion < countQuestion) {
			// variable question suivante
			currentQuestion = idQuestion+1;
			nextQuestion = idQuestion+2;
			
			$('.question'+currentQuestion+' .bouton-validation').fadeOut();
			
			// activation puce suivante
			$('#number'+nextQuestion).attr('class','number');
			
			// déroulement link
			$('.link').fadeOut();
			$('#number'+nextQuestion+' .link').fadeIn();
			
			// changement du numero de question
			$('.question-current').fadeOut(function(){
					$('.number-question').text(nextQuestion);
					$('.question-current').fadeIn();
			});
			// déroulement volet
			$('.question'+currentQuestion).fadeOut(function(){
				$('.question'+nextQuestion).fadeIn();	
			});
			// incrémentation question current
			idQuestion = idQuestion+1;
			
			$(this).fadeOut();
		}
	});
	
	//Clique flèche gauche
	/*$('.arrow-left').click(function(){
		if(idQuestion > 0) {
			
			// question courante
			currentQuestion = idQuestion+1;
			// question suivante
			nextQuestion = idQuestion;
			
			$('.question'+currentQuestion+' .bouton-validation').fadeOut();
			
			// reset des styles
			$('.puce').css({ 'background':'#9b9181','color':'#dddddd'});
			//changelent style puce
			$('#number'+nextQuestion+' .puce').css({ 'background':'#fff','color':'#CCDC90'});
			// déroulement link
			$('.link').fadeOut();
			$('#number'+nextQuestion+' .link').fadeIn();
			
			// déroulement volet
			$('.question'+currentQuestion).fadeOut(function(){
				$('.question'+nextQuestion).fadeIn();
				// changement du numero de question
				$('.question-current').fadeIn(function(){
					$('.number-question').text(nextQuestion);
				});
			});
			
			// changement du numero de question
			var numQuestion = idQuestion;
			$('.question-current').fadeOut(function(){
				$('.number-question').text(numQuestion);
				$('.question-current').fadeIn();
			});
			
			// incrémentation question current
			idQuestion = idQuestion-1;
		}
	});*/
	
	// Clique sur une réponse
	$('.reponses a').click(function () {
		$('.reponses a').removeClass('choix');
		$(this).addClass('choix');
		// question courante
		currentQuestion = idQuestion+1;
		$('.question'+currentQuestion+' .bouton-validation').fadeIn();
	});
	
	var nbreCheck = 0;
	
	// Clique sur bouton valider
	$('.bouton-validation').click(function(){
		// nombre de question répondue
		nbreCheck = nbreCheck+1;	
		// question courante			   
		currentQuestion = idQuestion+1;
		
		$('.question'+currentQuestion+' .reponses ul li a').each(function(){
			if (($(this).attr('class') == 'v') || ($(this).attr('class') == 'v choix')) {
				$(this).css({'background-color':'#ccdc90','color':'#fff'});
			}else{
				$(this).css({'background-color':'#e27979','color':'#fff'});	
			}
		});
		
		$('.message-reponse').text('');
		// pour chaque réponses cochées
		$('li a.choix').each(function(index) {
									  
			// Disparition du bouton
			$('.bouton-validation').fadeOut();
			
			// Si faux :
			if($(this).hasClass('f')) {
				$(this).removeClass('choix').css('margin-top','20px');
				// changement couleur puce faux
				$('#number'+(idQuestion+1)+' .puce').addClass('puce-faux');
				// message échec
				$('.message-reponse').css('background-color','#e27979');
				$('.message-reponse').fadeIn('fast', function(){
					$(this).text('Mauvaise réponse... :(');
					$(this).delay(2000).fadeOut(function() {
						$('.reponses','.question'+currentQuestion).fadeOut(function(){
							$('.explication','.question'+currentQuestion).fadeIn(function() {
								if(idQuestion < countQuestion) {
									$('.arrow-right a').css('display','block');
								}
							});
						});
					});
				});
				
			}else{
				
				// changement couleur puce vrai
				$('#number'+(idQuestion+1)+' .puce').addClass('puce-vrai');
				// Si vrai :
				score = score + 1;
				// message réussite
				$('.message-reponse').css('background-color','#ccdc90');
				$('.message-reponse').fadeIn('fast', function(){
					$(this).text('Bonne réponse ! :)');
					$(this).delay(2000).fadeOut(function() {
						$('.reponses','.question'+currentQuestion).fadeOut(function(){
							$('.explication','.question'+currentQuestion).fadeIn(function() {
								// affichage flèches
								if(idQuestion < countQuestion) {
									$('.arrow-right a').css('display','block');
								}
							});
						});
					});
				});
			}
		});
		
		if(nbreCheck == countQuestion+1) {
			var percent = ( score / (countQuestion+1) ) * 100;
			
			$('.final').fadeIn().text('Tu a terminé le questionnaire ! Ton score est de '+score+'/'+(countQuestion+1)+' soit '+percent.toFixed(0)+'% de bonnes réponses');
			
			
			if(percent > 50){
				$('.score').css('color','#ccdc90');
			}else{
				$('.score').css('color','#e27979');
			}
		}
		
		$('.reponses a');
		$('.score').text('score : '+score+'/'+(countQuestion+1));	
	});	
});