const express = require('express')
const app = express()
var msg = 'Hello World'
const port = 80

app.get('/', (req, res) => {
    res.send('Hello World!')
  })

  app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
  })