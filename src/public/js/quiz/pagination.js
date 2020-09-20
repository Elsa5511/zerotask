$(document).ready(function() {
    var browserWidth = window.innerWidth || document.documentElement.clientWidth;
    var questionRadius = 3;
    if (browserWidth < 600) {
        questionRadius = 2;
    }
    
    var quizPagination = $('.quiz.pagination');
    
    /* Showing active question */
    var activeQuestion = quizPagination.find('a.active').parent();
    activeQuestion.addClass('visible');
    
    /* Showing next and prev links */
    activeQuestion.siblings(':first').addClass('visible');
    activeQuestion.siblings(':last').addClass('visible');
    
    /* Showing all questions within the set questionRadius */
    var prevQuestion = activeQuestion.prev();
    for (var i = 0; i < questionRadius; i++) {
        prevQuestion.addClass('visible');
        prevQuestion = prevQuestion.prev();
    }
    
    var nextQuestion = activeQuestion.next();
    for (var i = 0; i < questionRadius; i++) {
        nextQuestion.addClass('visible');
        nextQuestion = nextQuestion.next();
    }
});


