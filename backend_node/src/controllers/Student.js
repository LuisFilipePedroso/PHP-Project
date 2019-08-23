const StudentModel = require('../models/Student')
const Institution = require('../controllers/Institution')
const Minerate = require('./Minerate')

class Student {

    async get(req, res) {
        const students = await StudentModel.find().populate('institution')

        return res.json(students)
    }

    async getById(req, res) {
        const student = await StudentModel.findById(req.params.id)
        const data = {
            status: 'SUCCESS',
            payload: {
                student
            }
        }
        return res.json(data)
    }

    async getByName(req, res) {
        const { name } = req.params
        const student = await StudentModel.find({
            name
        }).populate('institution')

        return student
    }

    async create(req, res) {
        const student = await StudentModel.create(req.body)
        return res.json(student)
    }

    async update (req, res) {
        const { id } = req.params
        const student = await StudentModel.findByIdAndUpdate(id, req.body, { new: true })
        return res.json(student)
    }

    async search(req, res) {
        try {
            const { code } = req.params
            const student = await Minerate.minerate(code)     
            let result = null
            if(student.institution !== '') {
                const hasInstitution = await Institution.getByName(false, student.institution)
                if(hasInstitution.payload.instituion === null) {
                    result = await Institution.create({
                        body: {
                            name: student.institution
                        }
                    }, res)
                } else {
                    result = hasInstitution
                }
            }

            req = {
                ...req,
                params: {
                    name: student.name
                }
            }

            const hasData = await this.getByName(req)

            if(!hasData.length) {
                const studentRequest = {
                    body: {
                        code,
                        name: student.name,
                        generalRank: parseFloat(student.generalRank) || 0,
                        signedSince: student.signedSince,
                        solved: student.solved,
                        score: parseFloat(student.score.replace(',', '.')),
                        tried: student.tried,
                        submissions: student.submissions.replace(',', '.'),
                        institution: result !== null ? result.payload.institution._id : '5d516580ef745c0d0af36362'
                    }
                }

                await this.create(studentRequest, res)
            }

            const response = await this.getByName(req, res)

            return await res.json(response)
        } catch (e) {
            throw e
        }
    }
}

module.exports = new Student()