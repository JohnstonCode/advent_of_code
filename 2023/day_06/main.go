package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

type Race struct {
	time     int
	distance int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	part1 := part1(string(content))
	part2 := part2(string(content))

	fmt.Printf("Part1: %s\n", part1)
	fmt.Printf("Part2: %s\n", part2)
}

func part1(input string) string {
	sections := strings.Split(input, "\n")

	times := getNumsFromLine(sections[0])
	distances := getNumsFromLine(sections[1])
	var races []Race

	for i := 0; i < len(times); i++ {
		races = append(races, Race{
			time:     times[i],
			distance: distances[i],
		})
	}

	errorMargin := 1

	for _, race := range races {
		winningWays := 0

		for i := 1; i < race.time; i++ {
			if (race.time-i)*i > race.distance {
				winningWays++
			}
		}

		errorMargin *= winningWays
	}

	return strconv.Itoa(errorMargin)
}

func part2(input string) string {
	sections := strings.Split(input, "\n")

	time, _ := strconv.Atoi(strings.Replace(strings.Replace(sections[0], "Time:", "", -1), " ", "", -1))
	distance, _ := strconv.Atoi(strings.Replace(strings.Replace(sections[1], "Distance:", "", -1), " ", "", -1))

	winningWays := 0

	for i := 1; i < time; i++ {
		if (time-i)*i > distance {
			winningWays++
		}
	}

	return strconv.Itoa(winningWays)
}

func getNumsFromLine(line string) []int {
	line = strings.TrimSpace(strings.Replace(strings.Replace(line, "Time:", "", -1), "Distance:", "", -1))
	var nums []int

	for _, num := range strings.Split(line, " ") {
		if num == "" {
			continue
		}

		n, _ := strconv.Atoi(num)

		nums = append(nums, n)
	}

	return nums
}
