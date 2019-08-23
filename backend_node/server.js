const express = require('express')
const mongoose = require('mongoose')
const cors = require('cors')

const app = express()
app.use(cors())

mongoose.connect('mongodb://localhost:27017/fuckingproject', {
    useNewUrlParser: true
})

app.use(express.json())
app.use(express.urlencoded({ extended: true }))
app.use(require('./src/routes'))

app.listen(3333, () => {
    console.log('Server is running on port 3333')
})
