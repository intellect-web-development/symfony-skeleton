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
            scriptContent = "make -f ./.deploy/Makefile test-ci"
        }
        script {
            name = "Production Build"
            scriptContent = """
                REGISTRY=%deploy.registry% NAMESPACE=%deploy.namespace% IMAGE_TAG=%build.number% make -f ./.deploy/Makefile production-build
                REGISTRY=%deploy.registry% NAMESPACE=%deploy.namespace% IMAGE_TAG=%build.number% PASSWORD=%deploy.password% make -f ./.deploy/Makefile production-push
            """.trimIndent()
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
