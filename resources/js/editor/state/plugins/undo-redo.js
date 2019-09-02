import { cloneDeep } from 'lodash';

export const history = {
  store: null,
  history: [],
  currentIndex: -1,

  init(store) {
    this.store = store;
  },

  record(state) {
    if (this.currentIndex + 1 < this.history.length) {
      this.history.splice(this.currentIndex + 1);
    }

    this.history.push(state);
    this.currentIndex++;
  },

  undo() {
    const prevState = this.history[this.currentIndex - 1];

    this.store.replaceState(cloneDeep(prevState));
    this.currentIndex--;
  },

  canUndo() {
    return this.history.length > 0;
  },

  redo() {
    const prevState = this.history[this.currentIndex + 1];

    this.store.replaceState(cloneDeep(prevState));
    this.currentIndex++;
  },

  canRedo() {
    return this.history.length > 0;
  },
};

const RecordMutations = [
  'SET_STYLE_PROP',
];

const UndoRedo = (store) => {
  history.init(store);
  history.record(cloneDeep(store.state));

  store.subscribe((mutation, state) => {
    if (RecordMutations.includes(mutation.type)) {
      console.log(mutation);
      history.record(cloneDeep(state));
    }
  });
};

export default UndoRedo;
