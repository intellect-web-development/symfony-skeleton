package _Self.buildTypes

import jetbrains.buildServer.configs.kotlin.v2018_2.*
import jetbrains.buildServer.configs.kotlin.v2018_2.buildFeatures.perfmon
import jetbrains.buildServer.configs.kotlin.v2018_2.buildSteps.script
import jetbrains.buildServer.configs.kotlin.v2018_2.triggers.vcs

object Build : BuildType({
    name = "Build"

    artifactRules = "+:**/* => app.zip"
    publishArtifacts = PublishMode.ALWAYS
    params {
        param("deploy.host", "")
        param("deploy.port", "")
        param("deploy.password", "")
        param("deploy.namespace", "")
        param("deploy.registry", "")
    }
    vcs {
        root(HttpsGithubComIntellectWebDevelopmentFuturesTraderRefsHeadsMaster)
    }

    steps {
        script {
            name = "Test"
            scriptContent = "make test-ci"
        }
        script {
            name = "Production Build"
            scriptContent = """
                REGESTRY=%deploy.registry% NAMESPACE=%deploy.namespace% IMAGE_TAG=%build.number% make production-build
                REGESTRY=%deploy.registry% NAMESPACE=%deploy.namespace% IMAGE_TAG=%build.number% make production-push
            """.trimIndent()
        }
        script {
            name = "Deploy to Production"
            scriptContent = "BUILD_NUMBER=%build.number% PORT=%deploy.port% HOST=%deploy.host%  REGISTRY_FULL=%deploy.registry%/%deploy.namespace% REGESTRY=%deploy.registry% NAMESPACE=%deploy.namespace% PASSWORD=%deploy.password% make deploy"
        }
    }

    triggers {
        vcs {
        }
    }

    features {
        perfmon {
        }
    }
})
