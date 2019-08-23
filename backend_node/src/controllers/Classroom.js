const ClassroomModel = require('../models/Class')

class Classroom {

    async get(req, res) {
        try {
            const classes = await ClassroomModel.find()
            return res.json(classes)
        } catch (e) {
            throw e
        }
    }

    async getById(req, res) {
        try {
            const classes = await ClassroomModel.findById(req.params.id)
            return res.json(classes)
        } catch (e) {
            throw e
        }   
    }

    async create(req, res) {
        try {
            const classes = await ClassroomModel.create(req.body)
            return res.json(classes)
        } catch (e) {
            throw e
        }
    }

    async update (req, res) {
        try {
            const { id } = req.params
            const result = await ClassroomModel.findByIdAndUpdate(id, req.body, { new: true })
            return res.json(result)
        } catch (e) {
            throw e
        }
    }

    async destroy (req, res) {
        try {
            const { id } = req.params
            await ClassroomModel.findByIdAndRemove(id)
            return res.json({
                status: 'SUCCESS'
            })
        } catch (e) {
            throw e
        }
    }
}

module.exports = new Classroom()