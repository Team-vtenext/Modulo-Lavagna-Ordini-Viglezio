export const backend = {
  baseurl: "https://viglezio.vtenext.ch/",
  popupAutocloseTime: 3500,
};

export let orders;
export let jobs;

export function resetIntervals() {
  clearInterval(jobs);
  clearInterval(orders);
  jobs = setInterval(fetchData2, 60000);
  orders = setInterval(fetchData, 60000);
  fetchData();
  fetchData2();
}
