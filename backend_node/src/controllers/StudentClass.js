const StudentClassModel = require('../models/StudentClass')
const _ = require('lodash')

class StudentClass {

    async get(req, res) {
        try {
            const result = await StudentClassModel.find()
                .populate({
                    path: 'class_id',
                    options: { sort: { createdAt: -1 } }

                })
                .populate({
                    path: 'student_id',
                    options: { sort: { createdAt: -1 } }
                })
            return res.json(result)
        } catch (e) {
            throw e
        } 
    }

    async getByClasroomId(req, res) {
        try {
            const { class_id } = req.params
            let arrStudents = []

            let result = await StudentClassModel.find({
                class_id
            })
            .populate({
                path: 'class_id',
                options: { sort: { createdAt: -1 } }

            })
            .populate({
                path: 'student_id',
                options: { 
                    sort: { score: -1 } 
                }
            })
            .exec((err, students) => {
                students.map(student => {
                    arrStudents = arrStudents.concat(student.student_id)
                })

                arrStudents = _.orderBy(arrStudents, ['score'], ['desc'])

                const result = arrStudents.map(student => {
                    const changed = students.find(s => s.student_id.id === student.id)
                    return changed
                })

                return res.json(result)
            })
        } catch (e) {
            throw e
        }
    }

    async create(req, res) {
        try {
            const result = await StudentClassModel.create(req.body)
            return res.json(result)
        } catch (e) {
            throw e
        } 
    }

    async destroy(req, res) {
        try {
            await StudentClassModel.findByIdAndRemove(req.params.id)
            return res.json({
                msg: 'SUCCESS'
            })
        } catch (e) {
            throw e
        }
    }
}

module.exports = new StudentClass()