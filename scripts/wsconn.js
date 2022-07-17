var chosenLetters = []

function send (destination) {
  var inputText = $('#word-input')
  var word = inputText.val()
  var data = {} // data to be sent
  data.sessionID = sessionID
  data.destination = destination

  switch (destination) {
    case 'player-signature':
      data.username = $("#ally-name").html()
      log('Signed player on session ' + sessionID + '<hr>')
      break
    case 'word-input':
      if (word === '') {
        alert('You must insert a word')
        return
      }
      data.word = word
      $('#ally-word').html(word)
      inputText.val('')
      log('Word sent: ' + word)
      break
    case 'turn-strategy':
      var chosenLetterValues = []
      var chosenLetterKeys = []
      for (var button in chosenLetters) {
        chosenLetterValues.push($(chosenLetters[button]).val())
        chosenLetterKeys.push(button)
      }

      data.strategyKeys = chosenLetterKeys
      data.strategyValues = chosenLetterValues
      log('Sending turn strategy')
      break
  }

  var json = JSON.stringify(data)
  try {
    socket.send(json)
  } catch (ex) {
    log(ex)
  }
}

function onMessageReceived (message) {
  if (message.data === '2') {
    document.location.href = 'http://cstoica.zitec.zone/failed.php?failed=' + message.data
    return
  }

  var phases = []
  phases['1'] = 'Turn Beginning'
  phases['2'] = 'Summon Phase'
  phases['4'] = 'Strategy Phase'
  phases['8'] = 'Battle Phase'
  phases['16'] = 'Turn Ending'

  var data = JSON.parse(message.data)
  switch (data.destination) {
    case 'init':
      chosenLetters = []
      wordButtonsArray = []
      $('#enemy-word').html('')
      $('#ally-word').html('')
      $('#command-panel').html('')
    case 'turn-update':
      $('#turn-number').html('<h1>Turn #' + data['turn-number'] + '</h1><h2>' + phases[data['turn-phase']] + '</h2>')
      if (data['turn-phase'] != 2) {
        $('#word-input').prop('disabled', true)
      } else {
        $('#word-input').prop('disabled', false)
      }
      break
    case 'word-output':
      $('#enemy-word').html(data.word)
      $('#command-panel').css({opacity: 1})
      generateWordHTMLTable()
      sendWordsToHistory(data)
      break
    case 'battle-results':
      var commandPanel = $('#command-panel')
      var html = '<h2>' + data.message + '</h2>'
      html += '<button id=\'ready-button\'>Next Turn &#9655;</button>'
      commandPanel.html(html)
      $('#ready-button').click(function () {
        send('ready-new-turn')
      })
      break
    case 'update-names':
      $("#enemy-name").html(data.enemyName)
    case 'log':
      log(data.message)
      break
  }

  $('#enemy-word').html(message.data['word'])
}

function onConnectionSuccess (message) {
  send('player-signature')
}

var wordButtonsArray = []

function generateWordHTMLTable () {
  var allyWord = $('#ally-word').html()
  var enemyWord = $('#enemy-word').html()

  var html = '<table>'

  for (var c in allyWord) {
    if (allyWord[c] in wordButtonsArray) {
      continue
    }
    var arr = []

    html += '<tr><td style="width: 50px; height: 70px;"><input type=\'button\' class=\'ally-letter\' value=\'' + allyWord[c] + '\'></td>'
    html += '<td style="font-size: 20px; width: 50px; text-align: center;">&#8614</td>'
    for (var ch in enemyWord) {
      if (arr.indexOf(enemyWord[ch]) >= 0) {
        continue
      }
      arr.push(enemyWord[ch])
      html += '<td><input type=\'button\' id="' + allyWord[c] + '->' + enemyWord[ch] + '" class=\'enemy-letter class-' + allyWord[c] + '\' value=\'' + enemyWord[ch] + '\'></td>'
    }
    html += '</tr>'
    document.getElementById('command-panel').disabled = true
    $('#command-panel *').prop('disabled', true)
    wordButtonsArray[allyWord[c]] = arr
  }

  html += '</table><button id="ready-button">Ready!</button>'

  $('#command-panel').html(html)

  $('#ready-button').click(function () {
    $('#command-panel').prop('disabled', true)
    $('#command-panel *').prop('disabled', true)
    for (var index in chosenLetters) {
      $(chosenLetters[index]).css({
        'background': 'rgba(150, 255, 150, 1)',
        'border': '1px solid rgba(50, 255, 50, 1)',
        'color': 'black'
      })
    }

    // now send this data to the server
    send('turn-strategy')
    wordButtonsArray = []
    chosenLetters = []
  })

  $('.enemy-letter').click(function () {
    var buttonArray = document.getElementsByClassName(this.className)
    for (var button in buttonArray) {
      buttonArray[button].disabled = false
      if (chosenLetters.indexOf(buttonArray[button]) >= 0 && buttonArray[button] !== this) {
        chosenLetters.splice(chosenLetters.indexOf(buttonArray[button], 1))
      }
    }

    this.disabled = true
    chosenLetters[this.className[this.className.length - 1]] = this
  })
}

function sendWordsToHistory (data) {
  var html = $("#history-grid").html()
  html += "<tr>" +
    "<td class='history-input'>" + data['turn-number'] + "</td>" +
    "<td class='history-input'>" + $("#ally-word").html() + "</td>" +
    "<td class='history-input'>" + $("#enemy-word").html() + "</td>" +
    "</tr>"

  $("#history-grid").html(html)
}