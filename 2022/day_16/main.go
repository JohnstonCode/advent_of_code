package main

import (
	"fmt"
	"os"
	"regexp"
	"strconv"
	"strings"
)

type valve struct {
	flow    int
	tunnels []int
}

var DP map[int]int
var valves map[int]valve
var vMap map[string]int

func main() {
	content, _ := os.ReadFile("./input.txt")
	lines := strings.Split(string(content), "\n")
	vMap = make(map[string]int)
	valves = make(map[int]valve)
	DP = make(map[int]int)

	for i, line := range lines {
		p := strings.Split(line, " ")

		vMap[p[1]] = i
	}

	for _, line := range lines {
		r := regexp.MustCompile(`Valve (.*) has flow rate=(.*); tunnels? leads? to valves? (.*)`)
		matches := r.FindAllStringSubmatch(line, -1)
		v := matches[0][1]
		flow, _ := strconv.Atoi(matches[0][2])
		tunnelsString := strings.TrimSpace(matches[0][3])
		tunnels := make([]int, 0)

		for _, t := range strings.Split(tunnelsString, ", ") {
			tunnels = append(tunnels, vMap[t])
		}

		valves[vMap[v]] = valve{
			flow:    flow,
			tunnels: tunnels,
		}
	}

	part1 := totalPressure(vMap["AA"], 0, 30, 0)
	part2 := totalPressure(vMap["AA"], vMap["AA"], 26, 1)

	fmt.Println(part1)
	fmt.Println(part2)
}

func totalPressure(start int, opened int, time int, otherPlayers int) int {
	if time == 0 {
		if otherPlayers > 0 {
			return totalPressure(vMap["AA"], opened, 26, otherPlayers-1)
		}

		return 0
	}

	key := opened*len(valves)*31*2 + start*31*2 + time*2 + otherPlayers
	if v, ok := DP[key]; ok && v >= 0 {
		return DP[key]
	}

	ans := 0
	noP1 := (opened & (1 << start)) == 0
	if noP1 && valves[start].flow > 0 {
		newU := opened | 1<<start
		ans = max(ans, (time-1)*valves[start].flow+totalPressure(start, newU, time-1, otherPlayers))
	}

	for _, tunnel := range valves[start].tunnels {
		ans = max(ans, totalPressure(tunnel, opened, time-1, otherPlayers))
	}

	DP[key] = ans

	return ans
}

func max(a int, b int) int {
	if a > b {
		return a
	}

	return b
}
