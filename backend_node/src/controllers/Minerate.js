const axios = require('axios')


class Minerate {

    async minerate(id) {
        const { data: html } = await axios.get(`https://www.urionlinejudge.com.br/judge/en/profile/${id}`)
        const arr1 = html.split('<div class="pb-username">')
        const arr2 = arr1[1].split("</ul>")

        const grossName = arr2[0].split(`${id}">`)
        const name = grossName[1].split('</a>')[0]

        const grossGeneralRank = grossName[1].split('Place:</span>')
        let generalRank = grossGeneralRank[1].split('&')[0]
        generalRank =  generalRank.replace(',', '.').trim()

        const grossInstitution = grossGeneralRank[1].split("'name'>")
        const institution = grossInstitution[1].split('</i>')[0]

        const grossData = grossInstitution[1].split('Since:</span>')
        const signedSince = grossData[1].split('</li>')[0].trim()

        const grossScore = grossData[1].split('Points:</span>')
        const score = grossScore[1].split('</li>')[0].trim()
        
        const grossSolved = grossScore[1].split('Solved:</span>')
        const solved = grossSolved[1].split('</li>')[0].trim()

        const grossTried = grossSolved[1].split('Tried:</span>')
        const tried = grossTried[1].split('</li>')[0].trim()

        const grossSubmissions = grossSolved[1].split('Submissions:</span>')
        const submissions = grossSubmissions[1].split('</li>')[0].trim()

        return {
            name,
            generalRank,
            institution,
            signedSince,
            score,
            solved,
            tried,
            submissions
        }
    }
}

module.exports = new Minerate()