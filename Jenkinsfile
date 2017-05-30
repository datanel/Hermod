#!groovy

stage("Unit tests") {
    node('') {
        checkout scm

        wrap([$class: 'AnsiColorBuildWrapper']) {
            sh '''
            HERMOD_CONTAINER="hermod_test_$BUILD_NUMBER"
            export UID=$(id -u)
            export GID=$(id -g)

            cp phpunit.xml.dist phpunit.xml
            cp docker/config.env.dist docker/config_test.env
            echo "SYMFONY_ENV=dev" >> docker/config_test.env
            docker-compose -f docker/docker-compose.test.yml run composer_install
            docker-compose -f docker/docker-compose.test.yml build
            docker-compose -f docker/docker-compose.test.yml run --name $HERMOD_CONTAINER hermod_api
            docker-compose -f docker/docker-compose.test.yml down --remove-orphans
            '''
            junit 'junit.xml'
        }
    }
}

