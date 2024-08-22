pipeline {
    agent any
    options { timestamps () }

    stages {
        stage('Pull & Push') {
            steps([$class: 'BapSshPromotionPublisherPlugin']) {
                script {
                    List SERVERS = ["fanclub_Application"]
                    for(server_list in SERVERS){
                        sshPublisher(
                            publishers: [
                                sshPublisherDesc(
                                    configName: server_list, 
                                    transfers: [
                                        sshTransfer(
                                            cleanRemote: false, 
                                            excludes: '', 
                                            execCommand: '', 
                                            execTimeout: 120000, 
                                            flatten: false, 
                                            makeEmptyDirs: false, 
                                            noDefaultExcludes: false, 
                                            patternSeparator: '[, ]+', 
                                            remoteDirectory: '', 
                                            remoteDirectorySDF: false, 
                                            removePrefix: '', 
                                            sourceFiles: '**/*'
                                        )
                                    ], 
                                    usePromotionTimestamp: false, 
                                    useWorkspaceInPromotion: false, 
                                    verbose: false
                                )
                            ]
                        )
                    }
                }
            }
        } 
    }
}
