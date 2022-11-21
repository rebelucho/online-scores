let dataGame
let last_update
    // let gameData = { tournamentName: "", stage: "" }
    // let player1GameData = { name: "Игрок 1", require: 501 }
    // let player2GameData = { name: "Игрок 2", require: 501 }
    // let tournamentNameId = document.getElementById("tournamentName")
    // let stageId = document.getElementById("stage")
    // let gamePlayersCountId = document.getElementById("gamePlayersCount")
    // let gameNameId = document.getElementById("gameName")
let player1NameId = document.getElementById("player1Name")
let player2NameId = document.getElementById("player2Name")
let player1PTSId = document.getElementById("player1PTS")
let player2PTSId = document.getElementById("player2PTS")
let player1s20Id = document.getElementById("player1s20")
let player1s19Id = document.getElementById("player1s19")
let player1s18Id = document.getElementById("player1s18")
let player1s17Id = document.getElementById("player1s17")
let player1s16Id = document.getElementById("player1s16")
let player1s15Id = document.getElementById("player1s15")
let player1sBullId = document.getElementById("player1sBull")
let player1s20ScoreId = document.getElementById("player1s20Score")
let player1s19ScoreId = document.getElementById("player1s19Score")
let player1s18ScoreId = document.getElementById("player1s18Score")
let player1s17ScoreId = document.getElementById("player1s17Score")
let player1s16ScoreId = document.getElementById("player1s16Score")
let player1s15ScoreId = document.getElementById("player1s15Score")
let player1sBullScoreId = document.getElementById("player1sBullScore")
let player2s20Id = document.getElementById("player2s20")
let player2s19Id = document.getElementById("player2s19")
let player2s18Id = document.getElementById("player2s18")
let player2s17Id = document.getElementById("player2s17")
let player2s16Id = document.getElementById("player2s16")
let player2s15Id = document.getElementById("player2s15")
let player2sBullId = document.getElementById("player2sBull")
let player2s20ScoreId = document.getElementById("player2s20Score")
let player2s19ScoreId = document.getElementById("player2s19Score")
let player2s18ScoreId = document.getElementById("player2s18Score")
let player2s17ScoreId = document.getElementById("player2s17Score")
let player2s16ScoreId = document.getElementById("player2s16Score")
let player2s15ScoreId = document.getElementById("player2s15Score")
let player2sBullScoreId = document.getElementById("player2sBullScore")
let scoresId = document.getElementById("scoresId")
let s20 = document.getElementById("s20")
let s19 = document.getElementById("s19")
let s18 = document.getElementById("s18")
let s17 = document.getElementById("s17")
let s16 = document.getElementById("s16")
let s15 = document.getElementById("s15")
let sBull = document.getElementById("sBull")
const gameData = {}
const player1Data = {}
const player2Data = {}

let player1Pts = 0
let player2Pts = 0

let tournamentName
let stage
let gamePlayersCount
let gameName
let legBegin
let throwCurrent
let player1Name
let player1Name1
let player1Name2
let player1Name3
let player1Avg
let player1Legs
let player1Sets
let player1DartsThrown
let player1s20
let player1s19
let player1s18
let player1s17
let player1s16
let player1s15
let player1sBull
let player1AllDarts
let player1AllScores
let player2Name
let player2Name1
let player2Name2
let player2Name3
let player2Avg
let player2Legs
let player2Sets
let player2DartsThrown
let player2s20
let player2s19
let player2s18
let player2s17
let player2s16
let player2s15
let player2sBull
let player2AllDarts
let player2AllScores

function getScore() {
    $.post("getscore.php", { id: id, last_update: last_update }, function(data) {
        // console.log(data)
        if (data == 'no data') {}
        if (data != 'no data') {
            tournamentName
            stage = data.stage
            gamePlayersCount = data.gamePlayersCount
            gameName = data.gameName
            legBegin = data.legBegin
            throwCurrent = data.throwCurrent
            player1Name = data.player1Name
            player1Name1 = data.player1Name1
            player1Name2 = data.player1Name2
            player1Name3 = data.player1Name3
            player1Avg = data.player1Avg
            player1Legs = data.player1Legs
            player1Sets = data.player1Sets
            player1DartsThrown = data.player1DartsThrown
            player1Pts = data.player1Pts
            player1s20 = data.player1s20
            player1s19 = data.player1s19
            player1s18 = data.player1s18
            player1s17 = data.player1s17
            player1s16 = data.player1s16
            player1s15 = data.player1s15
            player1sBull = data.player1sBull
            player1AllDarts = data.player1AllDarts
            player1AllScores = data.player1AllScores
            player2Name = data.player2Name
            player2Name1 = data.player2Name1
            player2Name2 = data.player2Name2
            player2Name3 = data.player2Name3
            player2Avg = data.player2Avg
            player2Legs = data.player2Legs
            player2Sets = data.player2Sets
            player2DartsThrown = data.player2DartsThrown
            player2Pts = data.player2Pts
            player2s20 = data.player2s20
            player2s19 = data.player2s19
            player2s18 = data.player2s18
            player2s17 = data.player2s17
            player2s16 = data.player2s16
            player2s15 = data.player2s15
            player2sBull = data.player2sBull
            player2AllDarts = data.player2AllDarts
            player2AllScores = data.player2AllScores
                // for (var key in data) {
                //     //     console.log(key + ': ' + data[key])
                //     key = data[key]
                // }
            replaceGameData()
        }
    }, "json")

}



function sectorScore(player, sector, countDart) {
    if (countDart == 0) {
        var result = '-'
        return result
    }
    if (countDart == 1) {
        var result = '<div class="cricket-1dart"></div>'
        return result
    }
    if (countDart == 2) {
        var result = '<div class="cricket-2dart"></div>'
        return result
    }
    if (countDart == 3) {
        var result = '<div class="cricket-3dart"></div>'
        return result
    }
    if (countDart > 3) {
        var result = '<div class="cricket-3dart"></div>'
        return result
    }
}

function sectorScoreCounter(sector, countDart) {
    if (countDart == 0) {
        var result = '<span></span>'
        return result
    }
    if (countDart == 1) {
        var result = '<span></span>'
        return result
    }
    if (countDart == 2) {
        var result = '<span></span>'
        return result
    }
    if (countDart == 3) {
        var result = '<span></span>'
        return result
    }
    if (countDart > 3) {
        var result = '<span>+' + (countDart - 3) * sector + '</div>'
        return result
    }
}


function replaceGameData() {


    player1s20Id.innerHTML = sectorScore(1, 20, player1s20)
    player1s20ScoreId.innerHTML = sectorScoreCounter(20, player1s20)
    player1s19Id.innerHTML = sectorScore(1, 19, player1s19)
    player1s19ScoreId.innerHTML = sectorScoreCounter(19, player1s19)
    player1s18Id.innerHTML = sectorScore(1, 18, player1s18)
    player1s18ScoreId.innerHTML = sectorScoreCounter(18, player1s18)
    player1s17Id.innerHTML = sectorScore(1, 17, player1s17)
    player1s17ScoreId.innerHTML = sectorScoreCounter(17, player1s17)
    player1s16Id.innerHTML = sectorScore(1, 16, player1s16)
    player1s16ScoreId.innerHTML = sectorScoreCounter(16, player1s16)
    player1s15Id.innerHTML = sectorScore(1, 15, player1s15)
    player1s15ScoreId.innerHTML = sectorScoreCounter(15, player1s15)
    player1sBullId.innerHTML = sectorScore(1, 25, player1sBull)
    player1sBullScoreId.innerHTML = sectorScoreCounter(25, player1sBull)
    player2s20Id.innerHTML = sectorScore(2, 20, player2s20)
    player2s20ScoreId.innerHTML = sectorScoreCounter(20, player2s20)
    player2s19Id.innerHTML = sectorScore(2, 19, player2s19)
    player2s19ScoreId.innerHTML = sectorScoreCounter(19, player2s19)
    player2s18Id.innerHTML = sectorScore(2, 18, player2s18)
    player2s18ScoreId.innerHTML = sectorScoreCounter(18, player2s18)
    player2s17Id.innerHTML = sectorScore(2, 17, player2s17)
    player2s17ScoreId.innerHTML = sectorScoreCounter(17, player2s17)
    player2s16Id.innerHTML = sectorScore(2, 16, player2s16)
    player2s16ScoreId.innerHTML = sectorScoreCounter(16, player2s16)
    player2s15Id.innerHTML = sectorScore(2, 15, player2s15)
    player2s15ScoreId.innerHTML = sectorScoreCounter(15, player2s15)
    player2sBullId.innerHTML = sectorScore(2, 25, player2sBull)
    player2sBullScoreId.innerHTML = sectorScoreCounter(25, player2sBull)
    scoresId.innerHTML = '<span class="fs-2 fw-bold">' + player1Legs + ' : ' + player2Legs + '</span>'
    if (player1s20 >= 3 && player2s20 >= 3) { s20.innerHTML = '<span class="line-through fs-1 fw-bold"> 20 </span>' } else { s20.innerHTML = '<span class="fs-1 fw-bold">20</span >' }
    if (player1s19 >= 3 && player2s19 >= 3) { s19.innerHTML = '<span class="line-through fs-1 fw-bold"> 19 </span>' } else { s19.innerHTML = '<span class="fs-1 fw-bold">19</span >' }
    if (player1s18 >= 3 && player2s18 >= 3) { s18.innerHTML = '<span class="line-through fs-1 fw-bold"> 18 </span>' } else { s18.innerHTML = '<span class="fs-1 fw-bold">18</span >' }
    if (player1s17 >= 3 && player2s17 >= 3) { s17.innerHTML = '<span class="line-through fs-1 fw-bold"> 17 </span>' } else { s17.innerHTML = '<span class="fs-1 fw-bold">17</span >' }
    if (player1s16 >= 3 && player2s16 >= 3) { s16.innerHTML = '<span class="line-through fs-1 fw-bold"> 16 </span>' } else { s16.innerHTML = '<span class="fs-1 fw-bold">16</span >' }
    if (player1s15 >= 3 && player2s15 >= 3) { s15.innerHTML = '<span class="line-through fs-1 fw-bold"> 15 </span>' } else { s15.innerHTML = '<span class="fs-1 fw-bold">15</span >' }
    if (player1sBull >= 3 && player2sBull >= 3) { sBull.innerHTML = '<span class="line-through fs-1 fw-bold"> Bull </span>' } else { sBull.innerHTML = '<span class="fs-1 fw-bold">Bull</span >' }

    if (throwCurrent = 1) {
        player1NameId.innerHTML = '<span class="h2 text-success">' + player1Name + '</span>'
        player2NameId.innerHTML = '<span class="h2">' + player2Name + '</span>'
        if ((player1Pts - player2Pts) > 0) {
            player1PTSId.innerHTML = '<span class="text-success fw-bolder fs-2"> +' + (player1Pts - player2Pts) + '</span><br><span>' + player1Pts + '</span>'
            player2PTSId.innerHTML = '<span class="text-danger">' + player2Pts + '</span>'
        } else if ((player1Pts - player2Pts) < 0) {
            player1PTSId.innerHTML = '<span class="text-danger fw-bolder fs-2" >' + (player1Pts - player2Pts) + '</span><br><span>' + player1Pts + '</span>'
            player2PTSId.innerHTML = '<span class="text-success">' + player2Pts + '</span>'
        }
    } else if (throwCurrent = 2) {
        player1NameId.innerHTML = '<span class="h2">' + player1Name + '</span>'
        player2NameId.innerHTML = '<span class="h2 text-success fw-bolder fs-2">' + player2Name + '</span>'
        if ((player2Pts - player1Pts) > 0) {
            player1PTSId.innerHTML = '<span class="text-danger">' + player1Pts + '</span>'
            player2PTSId.innerHTML = '<span class="text-success fw-bolder fs-2"> +' + (player2Pts - player1Pts) + '</span><br><span>' + player2Pts + '</span>'
        } else if ((player2Pts - player1Pts) < 0) {
            player1PTSId.innerHTML = '<span class="text-success ">' + player1Pts + '</span>'
            player2PTSId.innerHTML = '<span class="text-danger">' + (player2Pts - player1Pts) + '</span> <span>' + player2Pts + '</span>'
        }
    } else {
        player1NameId.innerHTML = '<span class="h2">' + player1Name + '</span>'
        player2NameId.innerHTML = '<span class="h2">' + player2Name + '</span>'
        player1PTSId.innerHTML = player1Pts
        player2PTSId.innerHTML = player2Pts
    }

}


getScore()
setInterval(getScore, 2000)