import numberRollup from "@handmadeweb/number-rollup";

let numberRolleups = document.querySelectorAll('.bmcb-counter');

export default function () {
  if (numberRolleups.length) {
    console.log('yes')
    numberRollup();
  }
}