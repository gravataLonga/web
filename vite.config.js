/**
 * Part of this code is from Laravel Vite Plugin
 * https://github.com/laravel/vite-plugin/blob/1.x/src/index.ts
 */

import tailwindcss from '@tailwindcss/vite'
import homedir from 'os'
import path from 'path'
import dotenv from 'dotenv'
import fs from 'fs'
dotenv.config()


function valetMacConfigPath() {
    return path.resolve(homedir.homedir(), '.config', 'valet')
}

function getResolvedHost () {
    const configPath = valetMacConfigPath()
    return path.basename(process.cwd()) + '.' + resolveDevelopmentEnvironmentTld(configPath);
}

function resolveDevelopmentEnvironmentServerConfig() {
    const configPath = valetMacConfigPath()
    const resolvedHost = getResolvedHost();
    const keyPath = path.resolve(configPath, 'Certificates', `${resolvedHost}.key`)
    const certPath = path.resolve(configPath, 'Certificates', `${resolvedHost}.crt`)

    return {
        keyPath, certPath
    };
}

function resolveDevelopmentEnvironmentTld(configPath) {
    const configFile = path.resolve(configPath, 'config.json')
    const config = JSON.parse(fs.readFileSync(configFile, 'utf-8'))
    return config.tld
}

let httpCertificate = resolveDevelopmentEnvironmentServerConfig();

/** @type {import('vite').UserConfig} */
export default {
    plugins: [tailwindcss()],
    appType: 'mpa',
    root: './',
    server: {
        host: getResolvedHost(),
        https: {
            key: httpCertificate.keyPath,
            cert: httpCertificate.certPath
        },
        cors: {
            origin: [
                /^https?:\/\/(?:(?:[^:]+\.)?localhost|127\.0\.0\.1|\[::1\])(?::\d+)?$/,
                ...(process.env.APP_URL ? [process.env.APP_URL] : []),
                /^https?:\/\/.*\.test(:\d+)?$/
            ]
        }
    },
    publicDir: false,
    build: {
        emptyOutDir: false,
        manifest: 'manifest.json',
        sourcemap: true,
        outDir: 'public',
        rollupOptions: {
            input: ['./resources/css/app.css', './resources/js/app.js']
        }
    },
}