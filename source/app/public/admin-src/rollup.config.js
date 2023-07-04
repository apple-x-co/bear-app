import terser from '@rollup/plugin-terser';

export default {
    input: 'src/index.js',
    output: {
        dir: 'output',
        format: 'iife'
    },
    plugins: [terser()]
};
