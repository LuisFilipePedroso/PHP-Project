const mongoose = require('mongoose')

const Student = mongoose.Schema({
    code: {
        type: Number,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    generalRank: {
        type: Number,
        required: true,
    },
    signedSince: {
        type: Date,
        required: true,
    },
    solved: {
        type: Number,
        required: true,
    },
    score: {
        type: Number,
        required: true,
    },
    tried: {
        type: Number,
        required: true,
    },
    submissions: {
        type: Number,
        required: true,
    },
    institution: {
        type: mongoose.Schema.Types.ObjectId, ref: 'Institution'
    }
})

module.exports = mongoose.model('Student', Student)