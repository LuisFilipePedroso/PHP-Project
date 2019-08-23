const mongoose = require('mongoose')

const StudentClass = mongoose.Schema({
    class_id: {
        type: mongoose.Schema.Types.ObjectId, ref: 'Class'
    },
    student_id: {
        type: mongoose.Schema.Types.ObjectId, ref: 'Student'
    }
})

module.exports = mongoose.model('StudentClass', StudentClass)