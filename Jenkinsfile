#!groovy

stage("Unit tests") {
    node('') {
        checkout scm

        wrap([$class: 'AnsiColorBuildWrapper']) {
            sh '''
            HERMOD_CONTAINER="hermod_test_$BUILD_NUMBER"
            UID=$(id -u)
            GID=$(id -g)

            docker-compose -f docker/docker-compose.test.yml build
            docker-compose -f docker/docker-compose.test.yml run --name $HERMOD_CONTAINER hermod_api
            docker-compose -f docker/docker-compose.test.yml down --remove-orphans
            '''
            junit 'junit.xml'
        }
    }
}

