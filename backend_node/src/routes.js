const express = require('express')
const Router = express.Router()
const StudentController = require('./controllers/Student')
const InstitutionController = require('./controllers/Institution')
const ClassController = require('./controllers/Classroom')
const StudentClass = require('./controllers/StudentClass')
const Minerate = require('./controllers/Minerate')

Router.get('/teste', (req,res) => res.send('Ola'))
Router.get('/students', StudentController.get)
Router.post('/student/:id', StudentController.create)
Router.put('/student/:id', StudentController.update)
Router.get('/student/search/:code', StudentController.search.bind(StudentController))

Router.get('/institutions', InstitutionController.get)
Router.get('/institution/:id', InstitutionController.getById)
Router.post('/institution', InstitutionController.create)
Router.delete('/institution/:id', InstitutionController.destroy)

Router.get('/classes', ClassController.get)
Router.get('/class/:id', ClassController.getById)
Router.post('/class', ClassController.create)
Router.put('/class/:id', ClassController.update)
Router.delete('/class/:id', ClassController.destroy)

Router.get('/studentclass', StudentClass.get.bind(StudentClass))
Router.get('/studentclass/:class_id', StudentClass.getByClasroomId.bind(StudentClass))
Router.post('/studentclass', StudentClass.create.bind(StudentClass))
Router.delete('/studentclass/:id', StudentClass.destroy.bind(StudentClass))

// Router.get('/minerate', Minerate.get)

module.exports = Router