// Назначаем переменные
function setVar() {
  dategame = $('#dateValue').val();
  name = $('#nameValue').val();
  tag = $('#tagValue').val();
  pageno = 1;
  getgame();
};

function setTag(tagIn) {
  tag = tagIn
  document.getElementById('tagValue').value = tagIn
  getgame()
  }


// Берем данные по играм
function getgame(){
$.post("getgame.php", {date: dategame, name: name , tag: tag, pageno: pageno}, function(data) {
     $( "#game_list" ).html( data )
  })
}
