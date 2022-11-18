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
let player2s20Id = document.getElementById("player2s20")
let player2s19Id = document.getElementById("player2s19")
let player2s18Id = document.getElementById("player2s18")
let player2s17Id = document.getElementById("player2s17")
let player2s16Id = document.getElementById("player2s16")
let player2s15Id = document.getElementById("player2s15")
let player2sBullId = document.getElementById("player2sBull")
const gameData = {}
const player1Data = {}
const player2Data = {}

let player1PTS = 0
let player2PTS = 0

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
    if (countDart <= 3) {
        var result = 0
        return result
    }
    if (countDart > 3) {
        var result = (countDart - 3) * sector
        return result
    }
}


function replaceGameData() {
    player1NameId.innerHTML = player1Name
    player2NameId.innerHTML = player2Name
    player1s20Id.innerHTML = player1s20 + ' + ' + sectorScore(1, 20, player1s20) + 'pts'
    player1s19Id.innerHTML = player1s19 + ' + ' + sectorScore(1, 19, player1s18) + 'pts'
    player1s18Id.innerHTML = player1s18 + ' + ' + sectorScore(1, 18, player1s18) + 'pts'
    player1s17Id.innerHTML = player1s17 + ' + ' + sectorScore(1, 17, player1s17) + 'pts'
    player1s16Id.innerHTML = player1s16 + ' + ' + sectorScore(1, 16, player1s16) + 'pts'
    player1s15Id.innerHTML = player1s15 + ' + ' + sectorScore(1, 15, player1s15) + 'pts'
    player1sBullId.innerHTML = player1sBull + ' + ' + sectorScore(1, 25, player1sBull) + 'pts'
    player2s20Id.innerHTML = player2s20 + ' + ' + sectorScore(2, 20, player2s20) + 'pts'
    player2s19Id.innerHTML = player2s19 + ' + ' + sectorScore(2, 19, player2s19) + 'pts'
    player2s18Id.innerHTML = player2s18 + ' + ' + sectorScore(2, 18, player2s18) + 'pts'
    player2s17Id.innerHTML = player2s17 + ' + ' + sectorScore(2, 17, player2s17) + 'pts'
    player2s16Id.innerHTML = player2s16 + ' + ' + sectorScore(2, 16, player2s16) + 'pts'
    player2s15Id.innerHTML = player2s15 + ' + ' + sectorScore(2, 15, player2s15) + 'pts'
    player2sBullId.innerHTML = player2sBull + ' + ' + sectorScore(2, 25, player2sBull) + 'pts'
    player1PTSId.innerHTML = player1PTS
    player2PTSId.innerHTML = player2PTS


}


getScore()
setInterval(getScore, 2000)