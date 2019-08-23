const InstitutionModel = require('../models/Institution')

class Institution {

    async get(req, res) {
        const institutions = await InstitutionModel.find()

        return res.json(institutions)
    }

    async getById(req, res) {
        const institution = await InstitutionModel.findById(req.params.id)
        return {
            status: 'SUCCESS',
            payload: {
                institution
            }
        }
    }

    async getByName(all = true, name) {
        let result = null
        if(!all) {
            result = await InstitutionModel.findOne({
                name
            })    
        } else {
            result = await InstitutionModel.find({
                name
            })
        }

        return {
            status: 'SUCCESS',
            payload: {
                institution: result
            }
        }
    }

    async create(req, res) {
        const institution = await InstitutionModel.create(req.body)
        return res.json(institution)
    }

    async save(req, res) {
        if(req.body.length) {
            this.name = req.institution
            let result = await this.getByName(false)
            if(!result) {
                result = this.create(req)    
                if(result === 'SUCCESS') {
                    return await this.getByName(false)
                }
            } else {
                req = {
                    ...req,
                    params: {
                        id: result._id
                    }
                }
                return this.getById(req)
            }
        }
    }

    async update(req, res) {
        const { id } = req.params
        
        try {
            const result = await InstitutionModel.findByIdAndUpdate(
                id, 
                req.body, 
                { new: true }
            )
            return res.json(result)
        } catch (e) {
            return res.status(400).json({
                error: `Error on update: ${e}`
            })
        }
    }

    async destroy (req, res) {
        const { id } = req.params

        try {
            await InstitutionModel.findByIdAndRemove(id)
            return res.json({
                status: 'SUCCESS'
            })
        } catch (e) {
            return res.status(400).json({
                error: `Error on delete: ${e}`
            })
        }
    }
}

module.exports = new Institution()