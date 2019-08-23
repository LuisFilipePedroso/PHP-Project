const mongoose = require('mongoose')

const Class = mongoose.Schema({
    name: {
        type: String,
        required: true,
    },
})

module.exports = mongoose.model('Class', Class)