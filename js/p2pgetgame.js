// Данные по p2p играм
function p2pgetgame() {
    $.post("getgame.php", { list: "p2p", date: dategame, name: name, tag: tag, pageno: pageno, admGames: admGames }, function(data) {
        $("game_p2p_list").html(data);
    });
}