module.exports = {
  setupFiles: ['<rootDir>/resources/js/tests/unit/setup'],
  setupTestFrameworkScriptFile: '<rootDir>/resources/js/tests/unit/matchers',
  testMatch: ['**/resources/js/**/(*.)unit.js'],
  moduleFileExtensions: ['js', 'json', 'vue'],
  transform: {
    '^.+\\.vue$': 'vue-jest',
    '^.+\\.js$': 'babel-jest',
  },
  snapshotSerializers: ['jest-serializer-vue'],
  collectCoverage: true,
  collectCoverageFrom: ['resources/js/**/*.{js,vue}', '!**/node_modules/**'],
  coverageReporters: ['text-summary'],
  coverageDirectory: '<rootDir>/resources/js/tests/unit/coverage',
};
