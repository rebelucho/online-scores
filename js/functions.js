// let listSet

// Назначаем переменные
function setVar() {
    dategame = $('#dateValue').val();
    name = $('#nameValue').val();
    tag = $('#tagValue').val();
    pageno = 1;
    admGames = false;
    getgame();
};


function setTag(tagIn) {
    tag = tagIn
    document.getElementById('tagValue').value = tagIn
    getgame()
}


// Берем данные по играм
function getgame() {
    $.post("getgame.php", { list: listSet, date: dategame, name: name, tag: tag, pageno: pageno, admGames: admGames }, function(data) {
        $("#game_list").html(data)
    })
}

// Удаление игр из списка по запросу админа
function deleteGame(id) {
    // console.log(id)
    // let deleteGame = true
    $.post("getgame.php", { delete: "true", id: id });
    // .done(function(data) {
    // alert("Игра удалена ");
    // });
    getgame()
}

// Функция ответа на вызов в p2p играх
// function answerP2P(name, guid, key){
//     $.post("p2p.php", {name: name, guid: guid, key: key});
// }