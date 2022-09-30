// Назначаем переменные
function setVar() {
    dategame = $('#dateValue').val();
    name = $('#nameValue').val();
    getgame();
};

// Берем данные по играм
function getgame(){
  $.post("getgame.php", {date: dategame, name: name , tag: tag}, function(data) {
       $( "#game_list" ).html( data )
    })
  }
