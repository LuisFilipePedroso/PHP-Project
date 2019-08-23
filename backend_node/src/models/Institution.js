const mongoose = require('mongoose')

const Institution = mongoose.Schema({
    name: {
        type: String,
        required: true,
    },
})

module.exports = mongoose.model('Institution', Institution)