#!groovy

stage("Unit tests") {
    node('') {
        checkout scm

        wrap([$class: 'AnsiColorBuildWrapper']) {
            sh '''
            HERMOD_CONTAINER="hermod_test_$BUILD_NUMBER"
            export UID=$(id -u)
            export GID=$(id -g)

            DOCKER_COMPOSE="docker-compose -p "$HERMOD_CONTAINER" -f docker/docker-compose.test.yml"

            cp docker/config.env.dist docker/config_test.env
            echo "SYMFONY_ENV=dev" >> docker/config_test.env
            $DOCKER_COMPOSE run composer_install
            $DOCKER_COMPOSE build
            $DOCKER_COMPOSE run hermod_api
            $DOCKER_COMPOSE down --remove-orphans
            '''
            junit 'junit.xml'
        }
    }
}
